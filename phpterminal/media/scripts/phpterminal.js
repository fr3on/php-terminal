'use strict';

(function(context, document, window)
{
	var $w = window;
	var $d = document;
	var $ua = $w.navigator.userAgent;
	var $mobile = $ua.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i);
	var $ie = $ua.indexOf('MSIE') != -1;
	var $safari = /safari/i.test($ua);
	var $tag = function(x, cx) { return (cx || $d).getElementsByTagName(x); };
	var $class = function(x, cx) { return (cx || $d).getElementsByClassName(x); };
	var $id = function(x, cx) { return (cx || $d).getElementById(x); };
	var $create = function(x, v) { var e = $d.createElement(/^\s*<t(h|r|d)/.test(x) ? 'table' : 'div'); e.innerHTML = x; e = e.children.length == 1 ? e.children[0] : e.children; if ( ! undef(v)) $attr(e, v); return e; };
	var $html = $tag('html')[0];
	var $head = $tag('head')[0];
	var $body = $tag('body')[0];
	var repl = function(x, y, z) { return x.replace(y, z || ''); };
	var is = function(x, y) { return typeof x == y; };
	var undef = function(x) { return is(x, 'undefined'); };
	var delay = function(c, ms) { var x = Array.prototype.slice.call(arguments, 2); return setTimeout(function(){ return c.apply(null, x); }, ms); };
	var uniqid = function(x) { var hash = 5381; if (x.length === 0) return hash; for (var i = 0; i < x.length; i++) hash = ((hash << 5) + hash) ^ x.charCodeAt(i); return hash; };
	var camel = function(x) { return x.replace(/(\-[a-z])/g, function($1) { return $1.toUpperCase().replace('-',''); }); };
	var $clone = function(x) { return x.cloneNode(true); };
	var $css = function(x, k) { if ( ! undef(k) && ! is(k, 'string')) { for (var p in k) x.style[p] = k[p]; return; } var computed = (($w.getComputedStyle && $w.getComputedStyle(x, null)) || ($d.defaultView && $d.defaultView.getComputedStyle(x, '')) || x.currentStyle || x.style); if (undef(k)) return computed; else if (is(k, 'string')) return computed[k]; };
	var $cssi = function(x) { var s = $d.createElement('style'); s.appendChild($d.createTextNode(x)); $head.appendChild(s); };
	var $attr = function(x, k) { if (is(k, 'string')) return x.getAttribute(k); else for (var p in k) x.setAttribute(p, k[p]); };
	var $value = function(x) { if (x.tagName.toLowerCase() == 'select') return x.options[x.selectedIndex].hasAttribute('value') ? x.options[x.selectedIndex].value : x.options[x.selectedIndex].innerText; else if (x.getAttribute('type') == 'checkbox') return x.checked; else return x.value; };
	var _$ = {}; _$[/^\s*</] = $create; _$[/^\.[\w\-]+$/] = $class; _$[/^\w+$/] = $tag; _$[/^\#[\w\-]+$/] = $id;
	var $ = function(x, cx)
	{
		if (x == $d) return [ $d ];
		else if (x == $w) return [ $w ];
		else if (x == 'body') return [ $d.body || $d.body.parentNode || $d.getElementsByTagName('body')[0] ];

		if (x instanceof Array) return x;
		else if (x.nodeType && x.nodeType == 1) return [ x ];

		x = x.replace(/\s*$/, '').replace(/^\s*/, '');

		for (var k in _$) { var p = k.split('/'); if ((new RegExp(p[1], p[2])).test(x)) return Array.prototype.slice.call(_$[k](x.replace(/^(\.|\#)/, ''), cx)); }

		return Array.prototype.slice.call((cx || $d).querySelectorAll(x));
	};

	function $won(ev, c)
	{
		$w['$' + ev] = null;

		var dec = function()
		{
			clearTimeout($w['$' + ev]);

			$w['$' + ev] = setTimeout(function(e)
			{
				c();
			}, 10);
		};

		if ($w.attachEvent) $w.attachEvent('on' + ev, dec); else $w.addEventListener(ev, dec);
	}

	var $resize = function(x) { $won('resize', x); };
	var $scroll = function(x) { $won('scroll', x); };

	var $on = function(x, e, c, cx)
	{
		var lv = arguments.length == 4;
		var ev = e;
		var ex = x;
		var ec = c;

		if (lv)
		{
			ev = c;
			ex = e || x || $d;

			ec = function(ee)
			{
				var found, el = ee.target || ee.srcElement;

				while (el && el !== x)
				{
					var nodes = $(ex, el.parentNode || $d);
					var i = -1;

					while (nodes[++i] && nodes[i] != el);

					found = !! nodes[i];

					if (found) break;

					el = el.parentElement;
				}

				if (found) { if (cx.call(el, ee) === false) ee.preventDefault(); ee.stopPropagation(); }
			};
		}

		if (x.attachEvent) x.attachEvent('on' + ev, ec); else x.addEventListener(ev, ec);
	};

	var $off = function(x, e, c) { if (x.detachEvent) x.detachEvent('on' + e, c); else x.removeEventListener(e, c); };
	var $trigger = function(e, cx) { var ev = $d.createEvent ? new Event(event) : $d.createEventObject(); if ($d.createEvent) cx.dispatchEvent(ev); else cx.fireEvent('on' + e, ev); };

	function $mousepos(e, elem)
	{
		var pageX, pageY;

		if (typeof(window.pageYOffset) == 'number')
		{
	    	pageX = window.pageXOffset;
	    	pageY = window.pageYOffset;
		} else
		{
			pageX = document.documentElement.scrollLeft;
			pageY = document.documentElement.scrollTop;
		}

		var top = 0;
		var left = 0;

		while (elem && elem.tagName != 'body')
		{
			top += elem.offsetTop - elem.scrollTop;
			left += elem.offsetLeft - elem.scrollLeft;
			elem = elem.offsetParent;
		}

		var mouseX = e.clientX - left + pageX;
		var mouseY = e.clientY - top + pageY;

		return {
			x: mouseX,
			y: mouseY
		};
	}

	function $has_class(element, _class)
	{
		return typeof element.className == 'string' && element.className.split(' ').indexOf(_class) >= 0;
	}

	function $add_class(element, _class)
	{
		if ( ! $has_class(element, _class))
		{
			element.className += (element.className.length > 0 ? ' ' : '') + _class;
		}
	}

	function $remove_class(element, _class)
	{
		if ($has_class(element, _class))
		{
			var classes = element.className.split(' ');
			var index = classes.indexOf(_class);

			if (index != -1)
			{
				classes.splice(index, 1);
			}

			element.className = classes.join(' ');
		}
	}

	function $closest(element, _class)
	{
		var parent = element.parentNode;

		if ($has_class(parent, _class))
		{
			return parent;
		}

		while (parent && parent !== document)
		{
			if ($has_class(parent, _class))
			{
				return parent;
			}

			parent = parent.parentNode;
		}

		return null;
	}

	function $ajax(x)
	{
		x = $extend({ async: true, url: '', headers: {}, data: {}, contenttype: 'application/x-www-form-urlencoded; charset=UTF-8', responsetype: '', datatype: 'json', cache: false, type: 'post', timeout: 5000, success: function() {}, error: function() {} }, x);

		var r = $w.XMLHttpRequest ? new $w.XMLHttpRequest() : $w.ActiveXObject ? new $w.ActiveXObject('Microsoft.XMLHTTP') : false;
		if ( ! r) return;

		r.onreadystatechange = function()
		{
			if (r.readyState == 4)
			{
				if (r.status == 200)
				{
					if (x.responsetype != '')
					{
						x.success(r.response, r.status, r);
					} else
					{
						x.success(x.datatype == 'json' ? JSON.parse(r.responseText ? r.responseText : '') : r.responseText, r.status, r);
					}
				} else x.error(r.responseText, r.status, r);
			}
		};

		r.onerror = x.error;
		r.ontimeout = x.error;
		r.upload.onerror = x.error;

		r.open(x.type.toUpperCase(), x.url + (x.cache ? '' : ((x.url.indexOf('?') == -1 ? '?' : '&') + '_=' + Math.random())), x.async);
		r.timeout = x.timeout;

		if (x.contenttype != '')
		{
			r.setRequestHeader('Content-Type', x.contenttype);
		}

		for (var k in x.headers)
		{
			r.setRequestHeader(k, x.headers[k]);
		}

		if (x.responsetype != '')
		{
			r.responseType = x.responsetype;
		}

		if (x.contenttype.indexOf('x-www-form-urlencoded') != -1)
		{
			var d = [];

			for (var k in x.data)
			{
				if (x.data.hasOwnProperty(k)) d.push(encodeURIComponent(k) + '=' + encodeURIComponent(x.data[k]));
			}

			r.send(d.join('&'));
		} else
		{
			r.send(x.data);
		}

		return r;
	}

	//var $pixel = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
	var $pixel = decodeURIComponent('data%3Aimage%2Fgif%3Bbase64%2CR0lGODlhAQABAIAAAAAAAP%2F%2F%2FyH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

	function $imageload(src, callback)
	{
		var img;

		if (src instanceof Image)
		{
			img = src;
		} else
		{
			img = new Image();
			img.src = src;
		}

		if (img.complete && img.naturalHeight !== 0)
		{
			setTimeout( function()
			{
				callback(img);
			}, 100);
		} else
		{
			$on(img, 'load', function()
			{
				callback(img);
			});
		}
	}

	function $visible(el)
	{
		return !! (el.offsetWidth || el.offsetHeight || el.getClientRects().length);
	}

	function $inview(el)
	{
		if ( ! $visible(el))
		{
			return false;
		}

		var scroll = window.scrollY || window.pageYOffset;
		var viewport = { top: scroll, bottom: scroll + window.innerHeight };
		var top = el.getBoundingClientRect().top + scroll;
		var bounds = { top: top, bottom: top + el.clientHeight };

		return top < window.innerHeight || (bounds.bottom >= viewport.top && bounds.bottom <= viewport.bottom || bounds.top <= viewport.bottom && bounds.top >= viewport.top);
	}

	function $bind(context, func)
	{
		return function() { return func.apply(context, arguments); };
	}

	function $extend(s, d)
	{
		var c = 'constructor';
		if (s == null || ! is(s, 'object'))
			return s;
		if (s[c] == Date || s[c] == RegExp || s[c] == Function || s[c] == String || s[c] == Number || s[c] == Boolean)
			return new s[c](s);
		d = d || new s[c]();
		for (var n in s)
			d[n] = (typeof d[n] == 'undefined') ? $extend(s[n], null) : d[n];
		return d;
	}

	var Caret = function(textarea, context)
	{
		if ( ! (this instanceof Caret))
		{
			return new Caret(textarea, context);
		}

		this._container = null;
		this._background = null;
		this._output = null;
		this._autoheight = true;
		this._tabsize = 4;
		this._showwhitespace = true;
		this._linenumbers = true;
		this._html_highlight = false;
		this._selection_start = null;
		this._selection_end = null;
		this._textarea = textarea;
		this._errors = {};
		this._timeout = null;
		this._value = '';
		this._strip_html_div = null;
		this._change_callback = null;
		this._save_callback = null;
		this._quit_callback = null;

		return this;
	};

	Caret._global = {};

	Caret.prototype.init = function()
	{
		var self = this;

		var stylesheet =
		[
			'.caret { display: block; min-height: 0 !important; resize: none; width: 98%; }',
			'.caret-bar { position: fixed; height: 24px; line-height: 24px !important; background: #fff !important; color: #000 !important; white-space: pre; padding-left: 8px; padding-right: 8px; left: 0; right: 0;  }',
			'.caret-bar-top { top: 0; }',
			'.caret-bar-bottom { bottom: 0; }',
			'.caret, .caret-output, .caret-background { letter-spacing: 0 !important; background: transparent !important; border: none !important; outline: none !important; resize: none !important; border: none !important; margin: 0; padding: 0; text-indent: 0; font-family: monospace, monospace; line-height: 1.4 !important; letter-spacing: 0 !important; }',
			'.caret.caret-line-numbers + .caret-output > span, .caret.caret-line-numbers + .caret-output + .caret-background { margin-left: 40px; }',
			'.caret.caret-line-numbers, .caret.caret-line-numbers + .caret-background, .caret.caret-line-numbers + .caret-output + .caret-background { letter-spacing: 0 !important; padding-left: 40px; margin-left: 0; }',
			'.caret-output { counter-reset: caret-line-count; opacity: 1; }',
			'.caret-output > span { letter-spacing: 0 !important; padding: 0; line-height: 1; letter-spacing: 0 !important; font-size: inherit; font-family: inherit; color: transparent !important; }',
			'.caret-output > span > .caret-keyword { color: transparent !important; background: #999; }',
			'.caret-output > span > .caret-error { margin-left: 4px; background: #fdf2f5; color: #af1e2b; border: 1px solid #f8d5db; padding: 2px; }',
			'.caret-output > span:before { letter-spacing: 0 !important; content: counter(caret-line-count); counter-increment: caret-line-count; display: block; font-weight: bold; position: absolute; display: none; width: 32px; left: 0px; text-align: right; line-height: 1.4; font-size: inherit; font-family: monospace, monospace; color: #000; opacity: 0.3; }',
			'.caret.caret-line-numbers + .caret-output > span:before { display: inline; color: inherit; }',
			'.caret, .caret-output { outline: none; white-space: pre !important; tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4; -webkit-tab-size: 4; background-color: transparent; z-index: 0;}',
			'.caret-output { overflow: hidden; }',
			'.caret-output > span { line-height: 1.4; color: inherit; }',
			'.caret:focus, .caret-background:focus { outline: none; }',
			'.caret-background { outline: none; display: block; position: absolute; top: 0; left: 0; z-index: -1; opacity: 0.3; }',
			'.caret-output { display: block; z-index: -2; position: absolute; top: 0; left: 0; }'
		];

		$cssi(stylesheet.join("\n"));

		this._container = this._textarea.parentNode.parentNode;
		this._top_bar = $class('caret-bar-top', this._container)[0];
		this._bottom_bar = $class('caret-bar-bottom', this._container)[0];


		this._textarea.parentNode.style.position = 'relative';

		if (this._linenumbers)
		{
			this._textarea.className += ' caret-line-numbers';
		}

		$attr(this._textarea, { spellcheck: false });
		$css(this._textarea, { 'tab-size': this._tabsize });

		this._textarea.insertAdjacentHTML('afterEnd', '<textarea class="caret caret-background" cols="80" rows="12"></textarea>');

		if (this._linenumbers || this._html_highlight)
		{
			this._textarea.insertAdjacentHTML('afterEnd', '<div class="caret-output"></div>');
		}

		this._background = $class('caret-background')[0];
		this._output = $class('caret-output')[0];

		if (this._background != null)
		{
			$css(this._background, { 'tab-size': this._tabsize });
		}

		if (this._autoheight)
		{
			$css(this._textarea, { 'overflow-y': 'hidden' });

			if (this._background != null)
			{
				$css(this._background, { 'overflow-y': 'hidden' });
			}

			if (this._output != null)
			{
				$css(this._output, { 'overflow-y': 'hidden' });
			}
		}

		this._strip_html_div = document.createElement('div');

		this._textarea.rows = 1;
		this._textarea.rows = this._textarea.value.split("\n").length;

		this._listen();
		this._update();

		this.hide();
	};

	Caret.prototype._process_background = function()
	{
		var content = this._textarea.value;

		if (this._showwhitespace)
		{
			content = content
				.replace(/ /g, "_")
				//.replace(/\t/g, "→".repeat(this._tabsize))
				.replace(/\n/g, "↵\n")
				.replace(/█\n/g, "█")
				.replace(/█↵\n/g, "█\n")
			.replace(/[a-zA-Z]/g, " ");
		}

		if (this._background != null)
		{
			this._background.value = content;

			this._background.scrollTop = this._textarea.scrollTop - 0.00001;
			this._background.scrollLeft = this._textarea.scrollLeft - 0.00001;
		}

		if (this._linenumbers || this._html_highlight)
		{
			$css(this._output,
			{
				'width': $css(this._textarea, 'width'),
				'height': $css(this._textarea, 'height'),
				'font-size': $css(this._textarea, 'font-size'),
				'font-family': $css(this._textarea, 'font-family')
			})

			content = this._textarea.value;

			if (this._html_highlight)
			{
				content = content.replace(/<((?=!\-\-)!\-\-[\s\S]*\-\-|((?=\?)\?[\s\S]*\?|((?=\/)\/[^.\-\d][^\/\]'"[!#$%&()*+,;<=>?@^`{|}~ ]*|[^.\-\d][^\/\]'"[!#$%&()*+,;<=>?@^`{|}~ ]*(?:\s[^.\-\d][^\/\]'"[!#$%&()*+,;<=>?@^`{|}~ ]*(?:=(?:"[^"]*"|'[^']*'|[^'"<\s]*))?)*)\s?\/?))>/gi,
					function(match)
					{
						return '<span class="caret-keyword">' + match.replace(/./gm, function(s)
						{
							return '&#' + s.charCodeAt(0) + ';';
						}) + '</span>';
					}
				);
			} else
			{
				content = content.replace(/./gm, function(s)
				{
					return '&#' + s.charCodeAt(0) + ';';
				});
			}

			this._output.innerHTML = (this._linenumbers ? '<span class="caret-line">' : '') + content.split("\n").join((this._linenumbers ? '&nbsp;</span><br /><span class="caret-line">' : '<br />')) + (this._linenumbers ? '&nbsp;</span>' : '');

			var lines = this._output.getElementsByClassName('caret-line');
			var line = '';

			for (var k in this._errors)
			{
				if (k <= lines.length)
				{
					line = this._errors[k].replace(/./gm, function(s)
					{
						return '&#' + s.charCodeAt(0) + ';';
					}).trim();

					if (line.length > 0)
					{
						lines[k - 1].innerHTML += '<span class="caret-error">' + line + '</span>';
					}
				}
			}

			this._output.scrollTop = this._textarea.scrollTop - 0.00001;
			this._output.scrollLeft = this._textarea.scrollLeft - 0.00001;
		}
	};

	Caret.prototype._process_content = function()
	{
		var self = this;

		this.save_selection();

		this._textarea.innerHTML = this._textarea.value;

		this.restore_selection();
	};

	Caret.prototype._update = function()
	{
		var self = this;

		this._process_content();

		if (this._autoheight)
		{
			self._textarea.rows = 1;
			self._textarea.rows = self._textarea.value.split("\n").length;

			var height = parseInt($css(this._textarea, 'height')) + 'px';

			this._textarea.parentNode.style.height = height;

			if (this._background != null)
			{
				this._background.style.height = height;
			}

			if (this._output != null)
			{
				this._output.style.height = height;
			}
		}

		this._process_background();

		clearTimeout(this._timeout);

		this._timeout = setTimeout( function()
		{
			if (self._value != self._textarea.value)
			{
				if (self._change_callback)
				{
					self._change_callback();
				}

				//self._update();
				self._value = self._textarea.value;
			}
		}, 500);
	};

	Caret.prototype._listen = function()
	{
		var self = this;

		$on(this._textarea, 'input', function()
		{
			self._textarea.rows = 1;
			self._textarea.rows = self._textarea.value.split("\n").length;
		});

		$on(this._textarea, 'scroll', function()
		{
			self._process_background()
		});

		$on(this._textarea, 'select', function()
		{

		});

		$on(this._textarea, 'change', function()
		{
			self._update();
		});

		$on(this._textarea, 'mouseup', function()
		{
			self._update();
		});

		$on(this._textarea, 'mousedown', function()
		{

		});

		$on(this._textarea, 'keyup', function(e)
		{
			self._update();
		});

		$on(this._textarea, 'keydown', function(e)
		{
			var key = e.keyCode || e.which;

			if (e.ctrlKey)
			{
				switch (key)
				{
					case 48:
						self.quit();
					break;

					case 50:
						self.save();
					break;
				}
			}

			if (key == 9)
			{
				e.preventDefault();

				var s = this.selectionStart;

				this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
				this.selectionEnd = s + 1;
			}

			self._update();
		});
	};

	Caret.prototype.get_position = function()
	{
		return this._textarea.selectionStart;
	};

	Caret.prototype.set_position = function(position)
	{
		this._textarea.selectionStart	= position;
		this._textarea.selectionEnd = position;
		this._textarea.focus();
	};

	Caret.prototype.has_selection = function()
	{
		if (this._textarea.selectionStart == this._textarea.selectionEnd)
		{
			return false;
		}

		return true;
	};

	Caret.prototype.save_selection = function()
	{
		this._selection_start = this._textarea.selectionStart;
		this._selection_end = this._textarea.selectionEnd;
	};

	Caret.prototype.restore_selection = function()
	{
		if (this._selection_start != this._selection_end)
		{
			this.select(this._selection_start, this._selection_end);
		}
	};

	Caret.prototype.clear_selection = function()
	{
		if (this._selection_start != this._selection_end)
		{
			this._textarea.value = this._textarea.value.slice(0, this._selection_start) + this._textarea.value.slice(this._selection_end);
		}
	};

	Caret.prototype.get_selection = function()
	{
		return this._textarea.value.substring(this._textarea.selectionStart, this._textarea.selectionEnd);
	};

	Caret.prototype.select = function(start, end)
	{
		this._textarea.selectionStart = start;
		this._textarea.selectionEnd = end;
		this._textarea.focus();
	};

	Caret.prototype.insert = function(text, offset_selection)
	{
		var pos = this.get_position();

		this.save_selection();
		this._textarea.value = [ this.slice(0, pos), text, this._textarea.value.slice(pos) ].join('');
		this.set_position(pos + (offset_selection ? offset_selection : 0));
	};

	Caret.prototype.save = function()
	{
		if (this._save_callback)
		{
			this._save_callback(this._textarea.value);
		}

		this.hide();
	};

	Caret.prototype.quit = function()
	{
		if (this._quit_callback)
		{
			this._quit_callback();
		}

		this.hide();
	};

	Caret.prototype.show = function()
	{
		this._textarea.parentNode.parentNode.style.display = 'block';
	};

	Caret.prototype.hide = function()
	{
		this._textarea.parentNode.parentNode.style.display = 'none';
	};

	Caret.prototype.edit = function(data, change_callback, save_callback, quit_callback)
	{
		var self = this;

		this.show();

		this._textarea.value = data;
		this._change_callback = change_callback;
		this._save_callback = save_callback;
		this._quit_callback = quit_callback;

		this._update();
		this.set_position(0);

		setTimeout( function()
		{
			self._textarea.focus();
		}, 10);
	};

	Caret.prototype.set_top = function(data)
	{
		this._top_bar.innerHTML = data;
	};

	Caret.prototype.set_bottom = function(data)
	{
		this._bottom_bar.innerHTML = data;
	};

	Caret.prototype.error = function(line, message)
	{
		if (line < 1)
		{
			line = 1;
		}

		this._errors[line] = message;
		this._process_background();
	};

	Caret.prototype.clear_errors = function()
	{
		this._errors = {};
		this._process_background();
	};

	var PHPTerminal = function(target, options)
	{
		if ( ! (this instanceof PHPTerminal))
		{
			return new PHPTerminal(target, options);
		}

		this.options = $extend
		({
			username: 'username',
			host: 'phpterm',
			path: '',
			ajax_url: '',
			allow_html: true,
			upload_limit: 64 * 1024 * 1024,
			i18n:
			{
				no_response: 'Error: no response from the server',
				json_syntax: 'Error: failed to parse JSON response',
				saving: 'Saving...',
				uploading: 'Uploading...',
				http_400: 'HTTP Error: bad request',
				http_401: 'HTTP Error: unauthorized',
				http_403: 'HTTP Error: forbidden',
				http_404: 'HTTP Error: not found',
				http_405: 'HTTP Error: method not allowed',
				http_406: 'HTTP Error: not acceptable',
				http_408: 'HTTP Error: request timeout',
				http_500: 'HTTP Error: internal server error',
				http_501: 'HTTP Error: not implemented',
				http_502: 'HTTP Error: bad gateway',
				http_503: 'HTTP Error: service unavailable',
				http_504: 'HTTP Error: gateway timeout',
				http_505: 'HTTP Error: HTTP version not supported',
				http_511: 'HTTP Error: network authentication required'
			}
		}, options);

		var html = '<div class="phpterminal"><textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></textarea>' +

		'</div>';

		target.innerHTML = html;

		this.body = $tag('body')[0];
		this.uploadinput = $id('phpterminal-upload');
		this.container = $class('phpterminal', target)[0];
		this.textarea = $tag('textarea', this.container)[0];
		this.username = this.options.username;
		this.host = this.options.host;
		this.path = '';
		this.job = false;
		this.prompt = this.get_prompt();
		this.last_result = null;
		this.result_timeout = null;
		this.is_loading = false;

		this.textarea.value = this.prompt;
		this.textarea.resize = 'off';
		this.textarea.autocomplete = 'off';
		this.textarea.autocorrect = 'off';
		this.textarea.autocapitalize = 'off';
		this.textarea.spellcheck = false;

		this.editor = new Caret($class('caret')[0]);
		this.editor.init();

		this.ready = false;

		this.init();

		return this;
	};

	PHPTerminal._global = {};

	PHPTerminal.prototype.get_prompt = function()
	{
		return this.username + '@' + this.host + ' #' + (this.path.length > 0 ? '/' : '') + this.path + ' % ';
	};

	PHPTerminal.prototype.autoheight = function()
	{
		var phpterm = this;

		phpterm.textarea.rows = 1;
		phpterm.textarea.rows = 1 + Math.ceil((phpterm.textarea.scrollHeight - phpterm.textarea.th) / phpterm.textarea.th);
		phpterm.textarea.style.height = phpterm.textarea.scrollHeight + 'px';
		phpterm.textarea.scrollTop = phpterm.textarea.scrollTop - 0.00001;
		phpterm.textarea.scrollLeft = phpterm.textarea.scrollLeft - 0.00001;
	};

	PHPTerminal.prototype.update_prompt = function(new_prompt)
	{
		var phpterm = this;

		if (typeof new_prompt != 'undefined')
		{
			phpterm.textarea.value = phpterm.textarea.value.substring(phpterm.prompt);
			phpterm.textarea.value = new_prompt + phpterm.textarea.value;
			phpterm.prompt = new_prompt;
		}

		if (phpterm.textarea.value.substring(0, phpterm.prompt.length) != phpterm.prompt)
		{
			phpterm.textarea.value = phpterm.prompt;
		}

		if (phpterm.textarea.selectionStart < phpterm.prompt.length)
		{
			phpterm.textarea.selectionStart = phpterm.prompt.length;
		}

		if (phpterm.textarea.selectionEnd < phpterm.prompt.length)
		{
			phpterm.textarea.selectionEnd = phpterm.prompt.length;
		}
	};

	PHPTerminal.prototype.autocomplete = function()
	{
		var phpterm = this;
	};

	PHPTerminal.prototype.echo = function(text)
	{
		var phpterm = this;

		if ( ! phpterm.options.allow_html)
		{
			text = text.replace(/./gm, function(s)
			{
				return '&#' + s.charCodeAt(0) + ';';
			});
		}

		var result = document.createElement('p');
		result.innerHTML = text;

		phpterm.last_result = result;

		phpterm.container.insertBefore(result, phpterm.textarea);

		if (phpterm.is_loading)
		{
			phpterm.loading();
		}

		phpterm.container.parentNode.scrollTop = phpterm.container.parentNode.scrollHeight - phpterm.container.parentNode.clientHeight;
	};

	PHPTerminal.prototype.clear = function()
	{
		var phpterm = this;
		var output = $tag('p', phpterm.container);

		for (var i = output.length - 1; i >= 0; i--)
		{
			output[i].parentNode.removeChild(output[i]);
		}
	};

	PHPTerminal.prototype.edit = function(filename, data)
	{
		var phpterm = this;

		phpterm.editor.set_top(filename);
		phpterm.editor.set_bottom('CTRL+2 [Save    ]    CTRL+0 [Quit    ]');
		phpterm.editor.edit(data,
		function()
		{
		},
		function(data)
		{
			phpterm.textarea.focus();
			phpterm.echo(phpterm.options.i18n.saving);
			phpterm.exec('put ' + filename + ' data:text/plain;base64,' + btoa(data), true);
		}, function()
		{
			phpterm.textarea.focus();
		});
	};

	PHPTerminal.prototype.clean_loading = function()
	{
		var phpterm = this;
		var loading = $class('phpterm-loading', phpterm.container);

		for (var i = 0; i < loading.length; i++)
		{
			loading[i].className = '';
		}
	};

	PHPTerminal.prototype.loading = function()
	{
		var phpterm = this;

		phpterm.clean_loading();
		phpterm.is_loading = true;

		phpterm.textarea.style.display = 'none';

		if (phpterm.result_timeout)
		{
			clearTimeout(phpterm.result_timeout);
		}

		phpterm.loading_animation();
	};

	PHPTerminal.prototype.loading_animation = function()
	{
		var phpterm = this;

		if (phpterm.last_result == null)
		{
			return false;
		}

		switch (phpterm.last_result.className)
		{
			case 'phpterm-loading phpterm-loading-1':
				phpterm.last_result.className = 'phpterm-loading phpterm-loading-2';
			break;

			case 'phpterm-loading phpterm-loading-2':
				phpterm.last_result.className = 'phpterm-loading phpterm-loading-3';
			break;

			case 'phpterm-loading phpterm-loading-3':
				phpterm.last_result.className = 'phpterm-loading phpterm-loading-4';
			break;

			case 'phpterm-loading phpterm-loading-4':
			default:
				phpterm.last_result.className = 'phpterm-loading phpterm-loading-1';
			break;
		}

		if (phpterm.result_timeout)
		{
			clearTimeout(phpterm.result_timeout);
		}

		phpterm.result_timeout = setTimeout(function()
		{
			if (phpterm.is_loading)
			{
				phpterm.loading_animation();
			}
		}, 200);
	};

	PHPTerminal.prototype.done = function()
	{
		var phpterm = this;

		phpterm.clean_loading();
		phpterm.is_loading = false;
		phpterm.last_result = null;

		phpterm.textarea.style.display = 'block';
		phpterm.textarea.focus();

		if (phpterm.result_timeout)
		{
			clearTimeout(phpterm.result_timeout);
		}
	};

	PHPTerminal.prototype.on_response_success = function(response, status, xhr)
	{
		var phpterm = this;

		if (response)
		{
			try
			{
				response = JSON.parse(response);
			} catch (err)
			{
				phpterm.done();
				phpterm.echo(phpterm.options.i18n.json_syntax);

				return;
			}

			if ( ! response)
			{
				phpterm.done();
				phpterm.echo(phpterm.options.i18n.no_response);

				return;
			}

			if (response.clear)
			{
				phpterm.clear();
			} else if (response.upload)
			{

			} else if (response.download)
			{
				phpterm.download(response.download);
			}

			if (response.result)
			{
				if (response.result instanceof Array)
				{
					for (var i = 0; i < response.result.length; i++)
					{
						phpterm.echo(response.result[i]);
					}
				} else
				{
					phpterm.echo(response.result);
				}
			}

			if (response.done)
			{
				if (response.path || response.path == '')
				{
					phpterm.path = response.path;
					phpterm.prompt = phpterm.get_prompt();
					phpterm.update_prompt();
				} else if (response.edit)
				{
					phpterm.edit(response.file, response.data);
				}

				phpterm.done();
				phpterm.job = false;
			} else if (response.job)
			{
				phpterm.job = response.job;
				phpterm.exec('job ' + response.job, true);
			}
		} else
		{
			phpterm.done();
			phpterm.echo(phpterm.options.i18n.no_response);
		}
	};

	PHPTerminal.prototype.on_response_error = function(response, status)
	{
		var phpterm = this;

		if (typeof phpterm.options.i18n['http_' + status] != 'undefined')
		{
			phpterm.echo(phpterm.options.i18n['http_' + status]);
		} else
		{
			phpterm.echo('Error: ' + status);
		}


		phpterm.done();
	};

	PHPTerminal.prototype.exec = function(input, quiet)
	{
		var phpterm = this;

		if ( ! input || input.length == 0)
		{
			return false;
		}

		if (typeof quiet == 'undefined' || ! quiet)
		{
			phpterm.done();
			phpterm.echo(phpterm.get_prompt() + input);
			phpterm.loading();
		}

		var form = new FormData();

		form.append('MAX_FILE_SIZE', phpterm.options.upload_limit);
		form.append('action', 'phpterm_exec');
		form.append('username', phpterm.username);
		form.append('path', phpterm.path);
		form.append('input', input);

		$ajax
		({
			url: phpterm.options.ajax_url,
			data: form,
			type: 'post',
			timeout: 0,
			datatype: 'text',
			contenttype: '',
			headers: { enctype: 'multipart/form-data' },
			success: function (response, status, xhr) { phpterm.on_response_success(response, status, xhr); },
			error: function (response, status, xhr) { phpterm.on_response_error(response, status, xhr); }
		});

		phpterm.textarea.value = phpterm.prompt;
	};

	PHPTerminal.prototype.init = function()
	{
		var phpterm = this;

		phpterm.textarea.rows = 1;
		phpterm.textarea.th = phpterm.textarea.scrollHeight;
		phpterm.autoheight();

		phpterm.textarea.focus();

		$on(phpterm.uploadinput, 'change', function(e)
		{
			e = e || window.event;

			var file = e.target || e.srcElement;

			if (file.files && file.files.length)
			{
				phpterm.done();
				phpterm.loading();

				var name = file.value.split(String.fromCharCode(92)).pop();
				var reader = new FileReader();
				reader.readAsDataURL(file.files[0]);

				reader.onload = function(e)
				{
					phpterm.upload(name, e.target.result);
				};
			}
		});

		$on(phpterm.container.parentNode, 'click', function(e)
		{
			phpterm.textarea.focus();
		});

		$on(phpterm.textarea, 'mousemove', function()
		{
			phpterm.update_prompt();
		});

		$on(phpterm.textarea, 'mouseup', function()
		{
			phpterm.update_prompt();
		});

		$on(phpterm.textarea, 'mousedown', function()
		{
			phpterm.update_prompt();
		});

		$on(phpterm.textarea, 'paste', function(e)
		{
			if (phpterm.is_loading)
			{
				e.preventDefault();

				return false;
			}
		});

		$on(phpterm.textarea, 'keyup', function(e)
		{
			if (phpterm.is_loading)
			{
				e.preventDefault();

				return false;
			}

			phpterm.update_prompt();
			phpterm.autoheight();
		});

		$on(phpterm.textarea, 'keydown', function(e)
		{
			if (phpterm.is_loading)
			{
				e.preventDefault();

				return false;
			}

			phpterm.update_prompt();
			phpterm.autoheight();

			if (e.keyCode == 9)
			{
				phpterm.autocomplete();

				e.preventDefault();

				return false;
			} else if (e.keyCode == 13)
			{
				if (phpterm.textarea.value.substring(phpterm.prompt.length) == 'upload')
				{
					// fake command exec to open input file browser
					phpterm.done();
					phpterm.echo(phpterm.get_prompt() + phpterm.textarea.value.substring(phpterm.prompt.length));
					phpterm.textarea.value = phpterm.prompt;

					phpterm.uploadinput.focus();
					phpterm.uploadinput.click();
				} else
				{
					phpterm.exec(phpterm.textarea.value.substring(phpterm.prompt.length));
				}

				e.preventDefault();

				return false;
			}
		});

		$on(phpterm.textarea, 'keypress', function(e)
		{
			if (phpterm.is_loading)
			{
				e.preventDefault();

				return false;
			}

			phpterm.update_prompt();
			phpterm.autoheight();
		});

		$on(phpterm.textarea, 'change', function(e)
		{
			if (phpterm.is_loading)
			{
				e.preventDefault();

				return false;
			}

			phpterm.update_prompt();
			phpterm.autoheight();
		});

		$on(phpterm.textarea, 'focus', function()
		{
		});

		$resize( function()
		{
			phpterm.onresize();
		});
	};

	PHPTerminal.prototype.onresize = function()
	{
		var phpterm = this;

		phpterm.autoheight();
	};

	PHPTerminal.prototype.upload = function(name, dataurl)
	{
		var phpterm = this;

		var data = dataurl.split(',');
		var mime = data[0].match(/:(.*?);/)[1];
		var bin = atob(data[1]);
		var len = bin.length;
		var bytes = new Uint8Array(len);

		while (len--)
		{
			bytes[len] = bin.charCodeAt(len);
		}

		var blob = new Blob([ bytes ], { type: mime });
		var form = new FormData();

		form.append('MAX_FILE_SIZE', phpterm.options.upload_limit);
		form.append('action', 'phpterm_upload');
		form.append('username', phpterm.username);
		form.append('path', phpterm.path);
		form.append('name', name);
		form.append('file', blob);

		phpterm.done();
		phpterm.echo(phpterm.options.i18n.uploading);
		phpterm.loading();

		$ajax
		({
			url: phpterm.options.ajax_url,
			data: form,
			type: 'post',
			timeout: 0,
			datatype: 'text',
			contenttype: '',
			headers: { enctype: 'multipart/form-data' },
			success: function (response, status, xhr) { phpterm.on_response_success(response, status, xhr); },
			error: function (response, status, xhr) { phpterm.on_response_error(response, status, xhr); }
		});
	};

	PHPTerminal.prototype.download = function(filename)
	{
		var phpterm = this;

		var form = new FormData();

		form.append('MAX_FILE_SIZE', phpterm.options.upload_limit);
		form.append('action', 'phpterm_exec');
		form.append('username', phpterm.username);
		form.append('path', phpterm.path);
		form.append('input', 'download ' + filename);
		form.append('download', filename);

		$ajax
		({
			url: phpterm.options.ajax_url,
			data: form,
			type: 'post',
			timeout: 0,
			datatype: 'text',
			responsetype: 'blob',
			contenttype: '',
			headers: { enctype: 'multipart/form-data' },
			success: function (response, status, xhr)
			{
				if (response)
				{
					var disposition = xhr.getResponseHeader('Content-Disposition');

					if (disposition && disposition.indexOf('attachment') !== -1)
					{
						var filename = '';
						var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);

						if (matches != null && matches[1])
						{
							filename = matches[1].replace(/['"]/g, '');
						}

						if (typeof window.navigator.msSaveBlob !== 'undefined')
						{
							window.navigator.msSaveBlob(response, filename);
						} else
						{
							var URL = window.URL || window.webkitURL;
							var dataurl = URL.createObjectURL(new Blob([ response ]));

							if (filename)
							{
								var a = document.createElement('a');

								if (typeof a.download === 'undefined')
								{
									window.location.href = dataurl;
								} else
								{
									a.href = dataurl;
									a.download = filename;

									document.body.appendChild(a);
									a.click();
								}
							} else
							{
								window.location.href = dataurl;
							}

							setTimeout(function () { URL.revokeObjectURL(dataurl); }, 100);
						}

						phpterm.done();
					}
				} else
				{
					phpterm.done();
					phpterm.echo(phpterm.options.i18n.no_response);
				}
			},
			error: function (response, status, xhr) { phpterm.on_response_error(response, status, xhr); }
		});
	};

	window.PHPTerminal = PHPTerminal;
})(this, document, window);
