
if(!Files) var Files = {};

Files.Row = new Class({
	Implements: [Options, Events, Files.Template],
	initialize: function(object, options) {
		this.setOptions(options);

		$each(object, function(value, key) {
			this[key] = value;
		}.bind(this));

		this.path = object.path;
		this.identifier = object.path;
	}
});

Files.File = new Class({
	Extends: Files.Row,

	type: 'file',
	template: 'file',

	'delete': function(success, failure) {
		this.fireEvent('beforeDeleteRow');
		
		var that = this,
			request = new Request.JSON({
				url: Files.getUrl({path: that.path}),
				method: 'post',
				data: {
					'action': 'delete',
					'_token': Files.token
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}
					that.fireEvent('afterDeleteRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					if (xhr.status == 204) {
						// Mootools thinks it failed, weird
						return this.onSuccess();
					}
					
					response = xhr.responseText;
					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
						response = JSON.decode(xhr.responseText, true);
						error = response && response.error ? response.error : 'An error occurred during request';
						alert(error);
					}
					
					that.fireEvent('afterDeleteRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	}
});

Files.Image = new Class({
	Extends: Files.File,

	type: 'image',
	template: 'image',
	initialize: function(object, options) {
		this.parent(object, options);

		this.baseurl = Files.baseurl;

		this.image = this.baseurl+'/'+this.path;
	}
});


Files.Folder = new Class({
	Extends: Files.Row,

	type: 'folder',
	template: 'folder',

	getChildren: function(success, failure, extra_vars) {
		var path = this.path;
		var url = {
			view: 'nodes',
			folder: path
		};
		if (extra_vars) {
			url = $extend(url, extra_vars);
		}
		var url = Files.getUrl(url);
			
		Files.Folder.Request._onSuccess = success;
		Files.Folder.Request._onFailure = failure;
		Files.Folder.Request.options.url = url;
		Files.Folder.Request.get();
	},
	'add': function(success, failure) {
		this.fireEvent('beforeAddRow');
		
		var that = this;
			request = new Request.JSON({
				url: Files.getUrl({view: 'folder'}),
				method: 'post',
				data: {
					'_token': Files.token,
					'parent': Files.app.getPath(),
					'path': that.path
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}
	
					that.fireEvent('afterAddRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					response = xhr.responseText;
					
					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
						response = JSON.decode(xhr.responseText, true);
						error = response && response.error ? response.error : 'An error occurred during request';
						alert(error);
					}
					
					that.fireEvent('afterAddRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	},
	'delete': function(success, failure) {
		var that = this;
			request = new Request.JSON({
				url: Files.getUrl({view: 'folders', path: that.path}),
				method: 'post',
				data: {
					'action': 'delete',
					'_token': Files.token
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}
					
					that.fireEvent('afterDeleteRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					if (xhr.status == 204) {
						// Mootools thinks it failed, weird
						return this.onSuccess();
					}
					
					response = xhr.responseText;
					
					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
						response = JSON.decode(xhr.responseText, true);
						error = response && response.error ? response.error : 'An error occurred during request';
						alert(error);
					}
					
					that.fireEvent('afterDeleteRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	}
});


Files.Folder.Request = new Request.JSON({
	method: 'get',
	onSuccess: function(response, responseText) {
		if (typeof this._onSuccess == 'function') {
			this._onSuccess(response);
		}
	},
	onFailure: function(xhr) {
		if (typeof this._onFailure == 'function') {
			this._onFailure(xhr);
		}
		else {
			resp = JSON.decode(xhr.responseText, true);
			error = resp && resp.error ? resp.error : 'An error occurred during request';
			alert(error);
		}
	}
});