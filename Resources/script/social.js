app.social = new (function () {
	var factoryData = {
		'vk':{
			'jsurl':'vk.com/js/api/openapi.js'
		},
		'fb':{
			'jsurl':   'connect.facebook.net/en_US/all.js',
			'localapi':'user/profile/postfbwall'
		}
	}

	var factory = (function (type) {
		var that = this;

		this.logined = false;
		this.initialized = false;

		var stack = [];
		var stack_trigger = (function (response) {
			if (!that.logined) {
				return;
			}
			while (stack.length) {
				var el = stack.pop();
				if (!el) {
					continue;
				}
				el.call(that, response);
			}
		});

		this.login = (function (callback) {
			// TODO: а если логин отменен?
			if (!(window[type.toUpperCase()] && that.initialized)) {
				setTimeout(function () {
					that.login(callback);
				}, 100);
				return;
			}
			stack.push(callback);
			switch (type) {
				case 'fb':
					FB.login(function (response) {
						if (response.authResponse) {
							that.logined = true;
							stack_trigger(response);
						}
					}, {scope:'email,publish_stream'});
					break;
				case 'vk':
					VK.Auth.login(null, VK.access.FRIENDS);
					break;
			}
		});


		this.getUid = (function (callback) {
			if (!this.logined) {
				this.login(function () {
					this.getUid(callback);
				});
				return;
			}
			switch (type) {
				case 'fb':
					FB.api('/me', {fields:'id,first_name,last_name,username,email'}, function (response) {
						callback(response.id, response);
					});
					break;
				case 'vk':
					VK.Api.call('execute', {
						'code':'return API.getVariable({key: 1280});'
					}, function (data) {
						callback(data.response);
					});
					break;
			}
		});

		this.likeButton = (function (id, data) {
			if (!$('#' + id).size()) {
				return;
			}
			if (!this.logined) {
				this.login(function () {
					this.likeButton(id, data);
				});
				return;
			}
			VK.Widgets.Like(id, data);
		});

		this.getprofile = (function (callback) {
			if (!this.logined) {
				this.login(function () {
					this.getprofile(callback);
				});
				return;
			}
			VK.Api.call('execute', {
				'code':'return {me: API.getProfiles({uids: API.getVariable({key: 1280}), fields: "uid, first_name, last_name, nickname"})[0]};'
			}, function (data) {
				callback(data.response.me);
			});
		});

		this.getfriends = (function (callback) {
			if (!this.logined) {
				this.login(function () {
					this.getfriends(callback);
				});
				return;
			}
			switch (type) {
				case 'fb':
					FB.api('/me/friends', {fields:'name,id'}, function (response) {
						if (response.data) {
							var friends = response.data;
							var list = {};
							for (i = 0; i < friends.length; i++) {
								list[friends[i]['id']] = {
									'name': friends[i]['name'],
									'title':friends[i]['name'],
									'id':   friends[i]['id']
								};
							}
							callback(list);
						}
					});
					break;
				case 'vk':
					VK.Api.call('execute', {
						'code':'return {friends: API.getProfiles({uids: API.getFriends()})};'
					}, function (data) {
						if (data.response) {
							var friends = data.response.friends;
							var list = {};
							for (i = 0; i < friends.length; i++) {
								var name = friends[i]['first_name'] + ' ' + friends[i]['last_name'];
								list[friends[i]['uid']] = {
									'name': name,
									'title':name,
									'id':   friends[i]['uid']
								};
							}
							callback(list);
						}
					});
					break;
			}
		});

		var wallPost;
		switch (type) {
			case 'fb':
				wallPost = (function (uid, message, callback) {
					FB.getLoginStatus(function (response) {
						// TODO: так делать плохо
						if (response.status === 'connected') {
							$fn.query(factoryData.fb.localapi, function (ret) {
								callback(ret);
							}, {
								'message':   message,
								'uid_target':uid,
								'user_token':response.authResponse.accessToken
							}, {
								root:true
							});
						} else {
							// TODO: кучу проверок
							callback('Ошибка подключения');
						}
					});
				});
				break;
			case 'vk':
				wallPost = (function (uid, message, callback, captcha) {
					if (!captcha) {
						var args = {
							'owner_id':uid,
							'message': message
						};
					} else {
						var args = {
							'owner_id':   uid,
							'message':    message,
							'captcha_sid':captcha.sid,
							'captcha_key':captcha.key
						};
					}
					VK.Api.call('wall.post', args, function (data) {
						if (data.response) {
							return callback();
						}
						if (data.error) {
							if (data.error.error_msg == 'Wall post access is denied') {
								return callback();
							}
							if (data.error.error_code == 14) {
								if (data.error.error_msg == 'Operation denied by user') {
									return callback(false);
								}
								if (!data.error.captcha_img) {
									return data.error.error_msg;
								}
								return callback('vk', {
									'sid':     data.error.captcha_sid,
									'img':     data.error.captcha_img,
									'callback':(function (data) {
										wallPost(uid, message, callback, data);
									})
								});
							}
						}
						callback(false);
					});
				});
				break;
		}

		this.wallPost = (function (a, b, c, d, e, f) {
			if (!this.logined) {
				this.login(function () {
					this.wallPost(a, b, c, d, e, f);
				});
				return;
			}
			wallPost(a, b, c, d, e, f);
		});

		switch (type) {
			case 'fb':
				window.fbAsyncInit = (function () {
					that.initialized = true;
					app.log('Social network "' + type + '" was connected');
					var root = document.createElement('div');
					root.id = 'fb-root';
					root.style.display = 'none';
					document.body.appendChild(root);
					FB.init({
						appId:     CONST.werkint_social_idfb, // App ID
						channelUrl:CONST.werkint_social_xdpath + type, // Channel File
						status:    true, // check login status
						cookie:    true, // enable cookies to allow the server to access the session
						xfbml:     true  // parse XFBML
					});
				});
				break;
			case 'vk':
				window.vkAsyncInit = function () {
					that.initialized = true;
					app.log('Social network "' + type + '" was connected');
					VK.Observer.subscribe('auth.login', function (response) {
						that.logined = true;
						stack_trigger(response);
					});
					VK.init({
						apiId:            CONST.werkint_social_idvk,
						nameTransportPath:CONST.werkint_social_xdpath + type
					});
				};
				break;
		}

		// Load the SDK Asynchronously
		(function (d) {
			var js, id = 'jssdk-social-' + type, ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {
				return;
			}
			js = d.createElement('script');
			js.id = id;
			js.async = true;
			js.src = '//' + factoryData[type].jsurl;
			ref.parentNode.insertBefore(js, ref);
		}(document));
	});

	var list = {};

	list.vk = list.vkontakte = new factory('vk');
	list.fb = list.facebook = new factory('fb');

	this.get = (function (type) {
		return list[type];
	});
})();