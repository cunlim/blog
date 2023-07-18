class Tools {

	debug_flag = false;	// true 디버깅, false 노 디버깅
	constructor() {}

	delayer = (time) => new Promise( (resolve)=>setTimeout( ()=>resolve(), time ) );
	// fn();
	// async function fn() {
	// 	await tools.delayer( 2000 );
	// 	console.log(12);
	// }

	// tools.timeInit();
	// tools.timeInit('ajax');
	// var spend_time = tools.spendTime();
	// var spend_time = tools.spendTime('sql');
	// var spend_time = tools.spendTime('sum');
	time_temp = {};
	timeInit = function ( time_line = 'main' ) {
		var time = new Date();
		if ( JSON.stringify( this.time_temp ) === '{}' ) {
			this.time_temp[time_line]	= { 'last' : time, 'now' : time };
			this.time_temp['sum']		= { 'last' : time, 'now' : time };
		} else {
			this.time_temp[time_line]	= { 'last' : time, 'now' : time };
		}
		return time;
	}
	spendTime = function ( time_line = 'main' ) {
		this.time_temp[time_line]['now'] = new Date();
		var spends = this.time_temp[time_line]['now'] - this.time_temp[time_line]['last'];
		this.time_temp[time_line]['last'] = this.time_temp[time_line]['now'];
		return spends;
	}

	toggleWww = function ( domain ) {
		if ( !domain ) { domain = new URL(location).host; }
		if ( domain.includes('www.') ) {
			return domain.replace('www.', '');
		} else {
			return 'www.' + domain;
		}
	}

	// 글자 byte 계산 및 byte만큼 자르기 {
	// https://zzznara2.tistory.com/458
	getByteLength = function(s) {

		if (s == null || s.length == 0) {
			return 0;
		}
		var size = 0;

		for ( var i = 0; i < s.length; i++) {
			size += this.charByteSize(s.charAt(i));
		}

		return size;
	}

	cutByteLength = function(s, len) {

		if (s == null || s.length == 0) {
			return 0;
		}
		var size = 0;
		var rIndex = s.length;

		for ( var i = 0; i < s.length; i++) {
			size += this.charByteSize(s.charAt(i));
			if( size == len ) {
				rIndex = i + 1;
				break;
			} else if( size > len ) {
				rIndex = i;
				break;
			}
		}

		return s.substring(0, rIndex);
	}

	charByteSize = function(ch) {

		if (ch == null || ch.length == 0) {
			return 0;
		}

		var charCode = ch.charCodeAt(0);

		if (charCode <= 0x00007F) {
			return 1;
		} else if (charCode <= 0x0007FF) {
			return 2;
		} else if (charCode <= 0x00FFFF) {
			return 3;
		} else {
			return 4;
		}
	}
	// 글자 byte 계산 및 byte만큼 자르기 }

	reqGet = function ( url ) {
		fetch( url ).then((response) => response.json()).then((resp_data) => {
			if ( !resp_data || resp_data.code != 200 ) { return; }
		}).catch((error) => alert(error));
	}

	reqPost = function ( url, post_data, callback ) {
		var post_conf = {
			"method": "POST",
			"headers": {
				"content-type": "application/json"
			},
			"body": JSON.stringify( post_data )
		}
		fetch( url, post_conf ).then((response) => response.json()).then(callback)
		.catch((error) => alert(error));
	}

}
let tools = new Tools("John");
// tools.debug_flag = true;

