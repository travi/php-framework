"use strict";

/*!
	JavaScript: The Good Parts by Douglas Crockford.
	Copyright 2008 Yahoo! Inc., 978-0-596-51774-8
 */
if (typeof Object.create !== 'function') {
	Object.create = function (o) {
		var F = function () {};
		F.prototype = o;
		return new F();
	};
}

/*!
 Array Remove - By John Resig (MIT Licensed)
  */
Array.prototype.remove = function (from, to) {
	var rest = this.slice((to || from) + 1 || this.length);
	this.length = from < 0 ? this.length + from : from;
	return this.push.apply(this, rest);
};