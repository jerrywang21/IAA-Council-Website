/*	
 *	jQuery carouFredSel 2.3.1
 *	Demo's and documentation:
 *	caroufredsel.frebsite.nl
 *	
 *	Copyright (c) 2010 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Licensed under the MIT license.
 *	http://www.opensource.org/licenses/mit-license.php
 */

(function($) {
	$.fn.carouFredSel = function(o) {
		if (this.length > 1) {
			return this.each(function() {
				$(this).carouFredSel(o);
			});
		}

		this.init = function() {
			direction = (opts.direction == 'up' || opts.direction == 'left') ? 'next' : 'prev';

			if (typeof(opts.items) 						!= 'object') 	opts.items 						= { visible: opts.items };
			if (	  !opts.items.width)								opts.items.width 				= getItems($cfs).outerWidth(true);
			if (	  !opts.items.height)								opts.items.height				= getItems($cfs).outerHeight(true);
			if (typeof(opts.scroll.items)				!= 'number')	opts.scroll.items				= opts.items.visible;

			opts.auto		= getNaviObject(opts.auto, false, true);
			opts.prev		= getNaviObject(opts.prev);
			opts.next		= getNaviObject(opts.next);
			opts.pagination	= getNaviObject(opts.pagination, true);

			opts.auto		= $.extend({}, opts.scroll, opts.auto);
			opts.prev		= $.extend({}, opts.scroll, opts.prev);
			opts.next		= $.extend({}, opts.scroll, opts.next);
			opts.pagination	= $.extend({}, opts.scroll, opts.pagination);

			if (typeof(opts.scroll.duration) 			!= 'number')	opts.scroll.duration			= $.fn.carouFredSel.defaultDuration;

			if (typeof(opts.pagination.anchorBuilder)	!= 'function')	opts.pagination.anchorBuilder	= $.fn.carouFredSel.pageAnchorBuilder;
			if (typeof(opts.pagination.keys)			!= 'boolean')	opts.pagination.keys			= false;
			if (typeof(opts.auto.play)					!= 'boolean')	opts.auto.play					= true;
			if (typeof(opts.auto.nap)					!= 'boolean')	opts.auto.nap					= true;
			if (typeof(opts.auto.delay)					!= 'number')	opts.auto.delay					= 0;
			if (typeof(opts.auto.pauseDuration)			!= 'number')	opts.auto.pauseDuration			= (opts.auto.duration<10) ? 2500 : opts.auto.duration * 5
		};

		this.build = function() {
			$wrp.css({
				position: 'relative',
				overflow: 'hidden'
			});
			$cfs.css({
				position: 'absolute'
			});
			setSizes($cfs, opts);
		};

		this.bind_events = function() {
			$cfs
				.bind('pause', function(){
					if (autoInterval != null) {
						clearTimeout(autoInterval);
					}
				})
				.bind('play', function(e, d, f) {
					$cfs.trigger('pause');
					if (opts.auto.play) {
						if (d != 'prev' && d != 'next')	d = direction;
						if (typeof(f) != 'number')		f = 0;

						autoInterval = setTimeout(function() {
							if ($cfs.is(':animated'))	$cfs.trigger('play', d);
							else 						$cfs.trigger(d, opts.auto);
						}, opts.auto.pauseDuration + f);
					}
				})
				.bind('prev', function(e, sO, nI) {
					if ($cfs.is(':animated')) return false;
					if (opts.items.visible >= totalItems) {
						log('Not enough items: not scrolling');
						return false;
					}
					if (typeof(sO) == 'number') nI = sO;
					if (typeof(sO) != 'object') sO = opts.prev;
					if (typeof(nI) != 'number') nI = sO.items;
					if (typeof(nI) != 'number') {
						log('Not a valid number: not scrolling');
						return false;
					}

					if (!opts.circular) {
						var nulItem = totalItems - firstItem;
						if (nulItem - nI < 0) {
							nI = nulItem;
						}
						if (firstItem == 0) {
							nI = 0;
						}
					}

					firstItem += nI;
					if (firstItem >= totalItems) firstItem -= totalItems;

					if (!opts.circular && !opts.infinite) {
						if (firstItem == 0 && 
							opts.prev.button) opts.prev.button.addClass('disabled');
						if (opts.next.button) opts.next.button.removeClass('disabled');
					}
					if (nI == 0) {
						if (opts.infinite) $cfs.trigger('next', totalItems-opts.items.visible);
						return false;
					}

					getItems($cfs).filter(':gt('+(totalItems-nI-1)+')').prependTo($cfs);
					if (totalItems < opts.items.visible + nI) getItems($cfs).filter(':lt('+((opts.items.visible+nI)-totalItems)+')').clone(true).appendTo($cfs);

					var c_itm = getCurrentItems($cfs, opts, nI), 
						i_siz = getSizes(opts, getItems($cfs).filter(':lt('+nI+')')),
						w_siz = getSizes(opts, c_itm[0], true);

					var ani = {},
						wra = {},
						dur = sO.duration;
						 if (dur == 'auto')	dur = opts.scroll.duration / opts.scroll.items * nI;
					else if (dur < 10)		dur = i_siz[1] / dur;

					ani[i_siz[4]] = 0;
					wra[i_siz[0]] = w_siz[1];
					wra[i_siz[2]] = w_siz[3];

					if (sO.onBefore) sO.onBefore(c_itm[1], c_itm[0], w_siz[1], w_siz[3], dur);

					$wrp.animate(wra, {
						duration: dur,
						easing	: sO.easing
					});
					$cfs.data('cfs_numItems', nI)
						.data('cfs_slideObj', sO)
						.data('cfs_oldItems', c_itm[1])
						.data('cfs_newItems', c_itm[0])
						.data('cfs_w_siz1', w_siz[1])
						.data('cfs_w_siz2', w_siz[3])
						.css(i_siz[4], -i_siz[1])
						.animate(ani, {
							duration: dur,
							easing	: sO.easing,
							complete: function() {
								if (totalItems < opts.items.visible + $cfs.data('cfs_numItems')) {
									getItems($cfs).filter(':gt('+(totalItems-1)+')').remove();
								}
								if ($cfs.data('cfs_slideObj').onAfter){
									$cfs.data('cfs_slideObj').onAfter($cfs.data('cfs_oldItems'), $cfs.data('cfs_newItems'), $cfs.data('cfs_w_siz1'), $cfs.data('cfs_w_siz2'));
								}
							}
						});
					$cfs.trigger('updatePageStatus').trigger('play',['',dur]);
				})
				.bind('next', function(e, sO, nI) {
					if ($cfs.is(':animated')) return false;
					if (opts.items.visible >= totalItems) {
						log('Not enough items: not scrolling');
						return false;
					}
					if (typeof(sO) == 'number') nI = sO;
					if (typeof(sO) != 'object') sO = opts.next;
					if (typeof(nI) != 'number') nI = sO.items;
					if (typeof(nI) != 'number') {
						log('Not a valid number: not scrolling');
						return false;
					}

					if (!opts.circular) {
						if (firstItem == 0) {
							if (nI > totalItems - opts.items.visible) {
								nI = totalItems - opts.items.visible;
							}
						} else {
							if (firstItem - nI < opts.items.visible) {
								nI = firstItem - opts.items.visible;
							}
						}
					}

					firstItem -= nI;
					if (firstItem < 0) firstItem += totalItems;

					if (!opts.circular && !opts.infinite) {
						if (firstItem == opts.items.visible &&
							opts.next.button) opts.next.button.addClass('disabled');
						if (opts.prev.button) opts.prev.button.removeClass('disabled');
					}
					if (nI == 0) {
						if (opts.infinite) $cfs.trigger('prev', totalItems-opts.items.visible);
						return false;
					}

					if (totalItems < opts.items.visible + nI) getItems($cfs).filter(':lt('+((opts.items.visible+nI)-totalItems)+')').clone(true).appendTo($cfs);

					var c_itm = getCurrentItems($cfs, opts, nI),
						i_siz = getSizes(opts, getItems($cfs).filter(':lt('+nI+')')),
						w_siz = getSizes(opts, c_itm[1], true);

					var ani = {},
						wra = {},
						dur = sO.duration;
						 if (dur == 'auto')	dur = opts.scroll.duration / opts.scroll.items * nI;
					else if (dur < 10)		dur = i_siz[1] / dur;

					ani[i_siz[4]] = -i_siz[1];
					wra[i_siz[0]] = w_siz[1];
					wra[i_siz[2]] = w_siz[3];

					if (sO.onBefore) sO.onBefore(c_itm[0], c_itm[1], w_siz[1], w_siz[3], dur);

					$wrp.animate(wra,{
						duration: dur,
						easing	: sO.easing
					});
					$cfs.data('cfs_numItems', nI)
						.data('cfs_slideObj', sO)
						.data('cfs_oldItems', c_itm[0])
						.data('cfs_newItems',c_itm[1])
						.data('cfs_w_siz1', w_siz[1])
						.data('cfs_w_siz2', w_siz[3])
						.animate(ani, {
							duration: dur,
							easing	: sO.easing,
							complete: function() {
								if ($cfs.data('cfs_slideObj').onAfter){
									$cfs.data('cfs_slideObj').onAfter($cfs.data('cfs_oldItems'), $cfs.data('cfs_newItems'), $cfs.data('cfs_w_siz1'), $cfs.data('cfs_w_siz2'));
								}
								if (totalItems < opts.items.visible+$cfs.data('cfs_numItems')) {
									getItems($cfs).filter(':gt('+(totalItems-1)+')').remove();
								}
								$cfs.css(i_siz[4], 0);
								getItems($cfs).filter(':lt('+$cfs.data('cfs_numItems')+')').appendTo($cfs);
							}
						});
					$cfs.trigger('updatePageStatus').trigger('play',['',dur]);
				})
				.bind('scrollTo', function(e, num, dev, org, obj) {
					if ($cfs.is(':animated')) return false;

					num = getItemIndex(num, dev, org, firstItem, totalItems, $cfs);
					if (typeof(obj) != 'object') obj = false;
					if (num == 0) return false;

					if (opts.circular) {
						if (num < totalItems / 2) 	$cfs.trigger('next', [obj, num]);
						else 						$cfs.trigger('prev', [obj, totalItems-num]);
					} else {
						if (firstItem == 0 ||
							firstItem > num)		$cfs.trigger('next', [obj, num]);
						else						$cfs.trigger('prev', [obj, totalItems-num]);
					}
				})
				.bind('slideTo', function(e, a, b, c, d) {
					$cfs.trigger('scrollTo', [a, b, c, d]);
				})
				.bind('insertItem', function(e, itm, num, org, dev) {
					if (typeof(itm) == 'object' && typeof(itm.jquery) == 'undefined') itm = $(itm);
					if (typeof(itm) == 'string') itm = $(itm);
					if (typeof(itm) != 'object' || 
						typeof(itm.jquery) == 'undefined' || 
						itm.length == 0
					) {
						log('Not a valid object.');
						return false;
					}
					if (typeof(num) == 'undefined' || num == 'end') {
						$cfs.append(itm);
					} else {
							num = getItemIndex(num, dev, org, firstItem, totalItems, $cfs);
						var $cit = getItems($cfs).filter(':nth('+num+')');

						if ($cit.length) {
							if (num <= firstItem) firstItem += itm.length;
							$cit.before(itm);
						} else {
							$cfs.append(itm);
						}
					}
					totalItems = getItems($cfs).length;
					setSizes($cfs, opts);
					$cfs.trigger('updatePageStatus', true);
				})
				.bind('removeItem',function(e, num, org, dev) {
					if (typeof(num) == 'undefined' || num == 'end') {
						getItems($cfs).filter(':last').remove();
					} else {
							num = getItemIndex(num, dev, org, firstItem, totalItems, $cfs);
						var $cit = getItems($cfs).filter(':nth('+num+')');
						if ($cit.length){
							if (num < firstItem) firstItem -= $cit.length;
							$cit.remove();
						}
					}
					totalItems = getItems($cfs).length;
					setSizes($cfs, opts);
					$cfs.trigger('updatePageStatus', true);
				})
				.bind('updatePageStatus', function(e, bpa) {
					if (!opts.pagination.container) return false;
					if (typeof(bpa) == 'boolean' && bpa) {
						getItems(opts.pagination.container).remove();
						for (var a = 0; a < Math.ceil(totalItems/opts.items.visible); a++) {
							opts.pagination.container.append(opts.pagination.anchorBuilder(a+1));
						}
						getItems(opts.pagination.container).unbind('click').each(function(a) {
							$(this).click(function(e) {
								$cfs.trigger('scrollTo', [a * opts.items.visible, 0, true, opts.pagination]);
								e.preventDefault();
							});
						});
					}
					var nr = (firstItem == 0) ? 0 : Math.round((totalItems-firstItem)/opts.items.visible);
					getItems(opts.pagination.container).removeClass('selected').filter(':nth('+nr+')').addClass('selected')
				});

			//	pauseOnHover
			if (opts.auto.pauseOnHover && opts.auto.play) {
				$wrp.hover(
					function() { $cfs.trigger('pause'); },
					function() { $cfs.trigger('play');	}
				);
			}

			//	via prev-button
			if (opts.prev.button) {
				opts.prev.button.click(function(e) {
					$cfs.trigger('prev');
					e.preventDefault();
				});
				if (opts.prev.pauseOnHover && opts.auto.play) {
					opts.prev.button.hover(
						function() { $cfs.trigger('pause');	},
						function() { $cfs.trigger('play');	}
					);
				}
				if (!opts.circular && !opts.infinite) {
					opts.prev.button.addClass('disabled');
				}
			}

			//	via next-button
			if (opts.next.button) {
				opts.next.button.click(function(e) {
					$cfs.trigger('next');
					e.preventDefault();
				});
				if (opts.next.pauseOnHover && opts.auto.play) {
					opts.next.button.hover(
						function() { $cfs.trigger('pause');	},
						function() { $cfs.trigger('play');	}
					)
				}
			}

			//	via pagination
			if(opts.pagination.container) {
				$cfs.trigger('updatePageStatus', true);
				if (opts.pagination.pauseOnHover && opts.auto.play) {
					opts.pagination.container.hover(
						function() { $cfs.trigger('pause');	},
						function() { $cfs.trigger('play');	}
					);
				}
			}

			//	via keyboard
			if (opts.next.key || opts.prev.key) {
				$(document).keyup(function(e) {
					var k = e.keyCode;
					if (k == opts.next.key)	$cfs.trigger('next');
					if (k == opts.prev.key)	$cfs.trigger('prev');
				});
			}
			if (opts.pagination.keys) {
				$(document).keyup(function(e) {
					var k = e.keyCode;
					if (k >= 49 && k < 58) {
						k = (k-49) * opts.items.visible;
						if (k <= totalItems) {
							$cfs.trigger('scrollTo', [k, 0, true, opts.pagination]);
						}
					}
				});
			}

			//	via auto-play
			if (opts.auto.play) {
				$cfs.trigger('play', [direction, opts.auto.delay]);
				if ($.fn.nap && opts.auto.nap) {
					$cfs.nap('pause','play');
				}
			}
			return this;
		};

		this.destroy = function() {
			$cfs.css({
				width	: 'auto',
				height	: 'auto',
				position: 'static'
			});
			$cfs.unbind('pause')
				.unbind('play')
				.unbind('prev')
				.unbind('next')
				.unbind('scrollTo')
				.unbind('slideTo')
				.unbind('insertItem')
				.unbind('removeItem')
				.unbind('updatePageStatus');

			$wrp.replaceWith($cfs);
			return this;
		};

		this.configuration = function(option, value) {
			if (typeof(option) == 'undefined') {
				return opts;
			}
			var c = option.split('.'),
				d = opts,
				f = '';

			for (var e = 0; e < c.length; e++) {
				f = c[e];
				if (e < c.length-1) {
					d = d[f];
				}
			}
			if (typeof(value) == 'undefined') {
				return d[f];
			} else {
				d[f] = value;

				this.init();
				this.build();
				return this;
			}
		};

		var $cfs 			= $(this),
			$wrp			= $(this).wrap('<div class="caroufredsel_wrapper" />').parent(),
			opts 			= $.extend(true, {}, $.fn.carouFredSel.defaults, o),
			totalItems		= getItems($cfs).length,
			firstItem 		= 0,
			autoInterval	= null,
			direction		= 'next';
		
		this.init();
		this.build();
		this.bind_events();
		return this;
	};

	//	public
	$.fn.carouFredSel.defaultDuration = 500;
	$.fn.carouFredSel.defaults = {
		infinite	: true,
		circular	: true,
		direction	: 'left',
		items		: {
			visible		: 5
		},
		scroll		: {
			duration	: $.fn.carouFredSel.defaultDuration,
			easing		: 'swing',
			pauseOnHover: false
		}
	};
	$.fn.carouFredSel.pageAnchorBuilder = function(nr) {
		return '<a href="#"><span>'+nr+'</span></a>';
	};

	//	private
	function getKeyCode(k) {
		if (k == 'right')	return 39;
		if (k == 'left')	return 37;
		if (k == 'up')		return 38;
		if (k == 'down')	return 40;
		return -1
	};
	function getNaviObject(obj, pagi, auto) {
		if (typeof(pagi) != 'boolean') pagi = false;
		if (typeof(auto) != 'boolean') auto = false;

		if (typeof(obj) == 'undefined')	obj = {};
		if (typeof(obj) == 'string') {
			var temp = getKeyCode(obj);
			if (temp == -1) 			obj = $(obj);
			else 						obj = temp;
		}
		if (pagi) {
			if (typeof(obj.jquery) != 'undefined')	obj = { container: obj };
			if (typeof(obj) == 'boolean')			obj = { keys: obj };
			if( typeof(obj.container) == 'string')	obj.container = $(obj.container);

		} else if(auto) {
			if (typeof(obj) == 'boolean')			obj = { play: obj };
			if (typeof(obj) == 'number')			obj = { pauseDuration: obj };

		} else {
			if (typeof(obj.jquery) != 'undefined')	obj = { button: obj };
			if (typeof(obj) == 'number')			obj = { key: obj };
			if (typeof(obj.button) == 'string')		obj.button = $(obj.button);
			if (typeof(obj.key) == 'string')		obj.key = getKeyCode(obj.key);
		}
		return obj;
	};
	function getItems(a) {
		return $('> *', a);
	};
	function getCurrentItems(c, o, n) {
		var oi = getItems(c).filter(':lt('+o.items.visible+')'),
			ni = getItems(c).filter(':lt('+(o.items.visible+n)+'):gt('+(n-1)+')');
		return[oi, ni];
	};
	function getItemIndex(num, dev, org, firstItem, totalItems, $cfs) {
		if (typeof(num) == 'string') {
			if (isNaN(num)) num = $(num);
			else 			num = parseInt(num);
		}
		if (typeof(num) == 'object') {
			if (typeof(num.jquery) == 'undefined') num = $(num);
			num = getItems($cfs).index(num);
			if (typeof(org) != 'boolean') org = false;
		} else {
			if (typeof(org) != 'boolean') org = true;
		}
		if (isNaN(num))	num = 0;
		else 			num = parseInt(num);
		if (isNaN(dev))	dev = 0;
		else 			dev = parseInt(dev);

		if (org) {
			num += firstItem;
			if (num >= totalItems)	num -= totalItems;
		}
		num += dev;
		if (num >= totalItems)		num -= totalItems;
		if (num < 0)				num += totalItems;
		return num;
	};
	function getSizes(o, $i, wrap) {
		if (typeof(wrap) != 'boolean') wrap = false;
		var SZ = (o.direction == 'right' || o.direction == 'left') 
			? ['width', 'outerWidth', 'height', 'outerHeight', 'left']
			: ['height', 'outerHeight', 'width', 'outerWidth', 'top'];

		var s1 = 0,
			s2 = 0;

			 if (wrap && typeof(o[SZ[0]]) 		== 'number') 	s1 = o[SZ[0]];
		else if (		 typeof(o.items[SZ[0]]) == 'number') 	s1 = o.items[SZ[0]] * $i.length;
		else {
			$i.each(function() { 
				s1 += $(this)[SZ[1]](true); 
			});
		}

			 if (wrap && typeof(o[SZ[2]]) 		== 'number') 	s2 = o[SZ[2]];
		else if (		 typeof(o.items[SZ[2]]) == 'number') 	s2 = o.items[SZ[2]];
		else {
			$i.each(function() {
				var m = $(this)[SZ[3]](true);
				if (s2 < m) s2 = m;
			});
		}
		return [SZ[0], s1, SZ[2], s2, SZ[4]];
	};
	function setSizes($c, o) {
		var $w = $c.parent(),
			$i = getItems($c),
			ws = getSizes(o, $i.filter(':lt('+o.items.visible+')'), true),
			is = getSizes(o, $i);

		$w.css(ws[0], ws[1]).css(ws[2],ws[3]);
		$c.css(is[0], is[1]*2).css(is[2],is[3]);
	};
	function log(m){
		m = 'carouFredSel: ' + m;
		if (window.console && window.console.log) window.console.log(m);
		else try { console.log(m); } catch(err) { }
	};

})(jQuery);
