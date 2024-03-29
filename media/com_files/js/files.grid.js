
if(!Files) var Files = {};

Files.Grid = new Class({
	Implements: [Events, Options],

	options: {
		onClickFolder: $empty,
		onClickFile: $empty,
		onClickImage: $empty,
		onDeleteNode: $empty,
		onSwitchLayout: $empty,
		switcher: 'files-layout-switcher',
		cookie: 'com.files.view.files.switcher',
		layout: false,
		batch_delete: false,
		types: null // null for all or array to filter for folder, file and image
	},

	initialize: function(container, options) {
		this.setOptions(options);

		this.nodes = new Hash();
		this.container = document.id(container);

		if (this.options.switcher) {
			this.options.switcher = document.id(this.options.switcher);
		}

		if (this.options.batch_delete) {
			this.options.batch_delete = document.getElement(this.options.batch_delete);
		}

		if (this.options.cookie) {
			this.setLayout(Cookie.read(this.options.cookie));
		}
		else if (this.options.layout) {
			this.setLayout(this.options.layout);
		}
		this.render();
		this.attachEvents();
	},
	attachEvents: function() {

		var that = this,
			createEvent = function(selector, event_name) {
				that.container.addEvent(selector, function(e) {
					e.stop();
					that.fireEvent(event_name, arguments);
				});
			};
		createEvent('click:relay(.files-folder a.navigate)', 'clickFolder');
		createEvent('click:relay(.files-file a.navigate)', 'clickFile');
		createEvent('click:relay(.files-image a.navigate)', 'clickImage');

		/*
		 * Checkbox events
		 */
		var fireCheck = function(e) {
			if (e.target.get('tag') == 'input') {
				e.target.setProperty('checked', !e.target.getProperty('checked'));
			};
			var row = e.target.getParent('.files-node').retrieve('row');
			var checkbox = row.element.getElement('input[type=checkbox]');
			
			this.fireEvent('beforeCheckNode', {row: row, checkbox: checkbox});
			
			var old = checkbox.getProperty('checked');

			row.checked = !old;
			checkbox.setProperty('checked', !old);

			this.fireEvent('afterCheckNode', {row: row, checkbox: checkbox});
		};
		this.container.addEvent('click:relay(div[class=controls])', fireCheck.bind(this));
		
		/*
		 * Delete events
		 */
		var deleteEvt = function(e) {
			if (e.stop) {
				e.stop();
			}

			var path = e.target.getParent('.files-node').retrieve('path');
			this.erase(path);
		}.bind(this);

		this.container.addEvent('click:relay(.delete-node)', deleteEvt);		
		
		if (this.options.batch_delete) {
			var chain = new Chain(),
				chain_call = function() {
					chain.callChain();
				},
				that = this;
				
			this.addEvent('afterCheckNode', function() {
				var checked = this.container.getElements('input[type=checkbox]:checked');
				this.options.batch_delete.setProperty('disabled', !checked.length);
			}.bind(this));
				
			this.options.batch_delete.addEvent('click', function(e) {
				e.stop();
				that.addEvent('afterDeleteNode', chain_call);
				that.addEvent('afterDeleteNodeFail', chain_call);
				
				var checkboxes = this.container.getElements('input[type=checkbox].files-select');
				checkboxes.each(function(el) {
					if (!el.checked) {
						return;
					}
					chain.chain(function() {
						deleteEvt({target: el});
					});
				});
				chain.chain(function() {
					that.removeEvent('afterDeleteNode', chain_call);
					that.removeEvent('afterDeleteNodeFail', chain_call);
					chain.clearChain();
				});
				chain.callChain();
			}.bind(this));
		}

		if (this.options.switcher) {
			var that = this;
			this.options.switcher.addEvent('change', function(e) {
				e.stop();
				
				var value = this.get('value');
				that.setLayout(value);
			});
		}
	},
	erase: function(node) {
		if (typeof node === 'string') {
			node = this.nodes.get(node);
		}
		if (node) {
			this.fireEvent('beforeDeleteNode', {node: node});
			var success = function() {
				if (node.element) {
					node.element.dispose();
				}

				this.nodes.erase(node.path);
				
				this.fireEvent('afterDeleteNode', {node: node});
			}.bind(this),
				failure = function() {
					this.fireEvent('afterDeleteNodeFail', {node: node});
				}.bind(this);
			node['delete'](success, failure);
		}
	},
	render: function() {
		this.fireEvent('beforeRender');
		
		this.container.empty();
		this.root = new Files.Grid.Root();
		this.root.element.injectInside(this.container);

		this.renew();
		
		this.fireEvent('afterRender');
	},
	renderObject: function(object, position) {
		var position = position || 'alphabetical';

		object.element = object.render();
		object.element.store('path', object.path);
		object.element.store('row', object);
		
		this.fireEvent('beforeRenderObject', {object: object, position: position});

		if (position == 'last') {
			this.root.adopt(object.element, 'bottom');
		}
		else if (position == 'first') {
			this.root.adopt(object.element);
		}
		else {
			var index = this.nodes.filter(function(node){
				return node.type == object.type;
			}).getKeys();

			if (index.length === 0) {
				if (object.type === 'folder') {
					var keys = this.nodes.getKeys();
					if (keys.length) {
						// there are files so append it before the first file
						var target = this.nodes.get(keys[0]);
						object.element.inject(target.element, 'before');
					}
					else {
						this.root.adopt(object.element, 'bottom');
					}
				}
				else {
					this.root.adopt(object.element, 'bottom');
				}

			}
			else {
				index.push(object.path);
				index = index.sort();

				var obj_index = index.indexOf(object.path);
				var length = index.length;
				if (obj_index === 0) {
					var target = this.nodes.get(index[1]);
					object.element.inject(target.element, 'before');
				}
				else {
					var target = obj_index+1 === length ? index[length-2] : index[obj_index-1];
					target = this.nodes.get(target);
					object.element.inject(target.element, 'after');
				}
			}
		}

		this.fireEvent('afterRenderObject', {object: object, position: position});

		return object.element;
	},
	reset: function() {
		this.fireEvent('beforeReset');
		
		this.nodes.each(function(node) {
			if (node.element) {
				node.element.dispose();
			}
			this.nodes.erase(node.path);
		}.bind(this));
		
		this.fireEvent('afterReset');
	},
	insert: function(object, position) {
		this.fireEvent('beforeInsertNode', {object: object, position: position});
		
		if (!this.options.types || this.options.types.contains(object.type)) {
			this.renderObject(object, position);
			this.nodes.set(object.path, object);

			this.fireEvent('afterInsertNode', {node: object, position: position});
		}
	},
	/**
	 * Insert multiple rows, possibly coming from a JSON request
	 */
	insertRows: function(rows) {
		this.fireEvent('BeforeInsertRows', {rows: rows});
		
		$each(rows, function(row) {
			var cls = Files[row.type.capitalize()];
			var item = new cls(row);
			this.insert(item, 'last');
		}.bind(this));
		
		this.fireEvent('afterInsertRows', {rows: rows});
	},
	renew: function() {
		this.fireEvent('beforeRenew');
		
		var folders = this.getFolders(),
			files = this.getFiles();

		folders.each(function(folder) {
			var node = this.nodes.get(folder);
			if (node.element) {
				node.element.dispose();
			}
			this.renderObject(node, 'last');
		}.bind(this));
		files.each(function(file) {
			var node = this.nodes.get(file);
			if (node.element) {
				node.element.dispose();
			}
			this.renderObject(node, 'last');
			if (node.checked) {
				node.element.getElement('input[type=checkbox]').setProperty('checked', node.checked);
			}
		}.bind(this));
		
		this.fireEvent('afterRenew');
	},
	setLayout: function(layout) {
		if (layout) {
			this.fireEvent('beforeSetLayout', {layout: layout});
			
			Files.Template.layout = layout;
			if (this.options.switcher) {
				this.options.switcher.set('value', layout);
			}

			if (this.options.cookie) {
				Cookie.write(this.options.cookie, layout);
			}
			
			this.fireEvent('afterSetLayout', {layout: layout});
			
			this.render();
		}

	},
	getFolders: function() {
		return this.nodes.filter(function(node) {
			return node.type === 'folder';
		}).getKeys().sort();
	},
	getFiles: function() {
		return this.nodes.filter(function(node) {
			return node.type === 'file' || node.type == 'image';
		}).getKeys().sort();
	}
});

Files.Grid.Root = new Class({
	Implements: Files.Template,
	template: 'container',
	initialize: function() {
		this.element = this.render();
	},
	adopt: function(element, position) {
		position = position || 'top'; 
		var parent = this.element;
		if (this.element.get('tag') == 'table') {
			parent = this.element.getElement('tbody');
		}
		element.injectInside(parent, position);
	}
});