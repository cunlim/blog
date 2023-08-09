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


	setSearchDate = function ( type ) {
		const aday		= 1000 * 60 * 60 * 24;
		const now		= new Date();	// new Date() - aday * 2
		const tod		= new Date( now.getFullYear(), now.getMonth(), now.getDate() );
		const map_week	= [ 6, 0, 1, 2, 3, 4, 5 ];
		const week_i	= map_week[ now.getDay() ];
		const map = {
			"init"			: [ now														, now ],
			"this_date"		: [ tod														, now ],
			"last_date"		: [ new Date( tod - aday * ( 1			) )					, new Date( tod - 1000 ) ],
			"this_week"		: [ new Date( tod - aday * ( week_i		) )					, now ],
			"last_week"		: [ new Date( tod - aday * ( week_i + 7	) )					, new Date( tod - aday * (week_i + 1) ) ],
			"this_month"	: [ new Date( now.getFullYear(), now.getMonth()     , 1 )	, now ],
			"last_month"	: [ new Date( now.getFullYear(), now.getMonth() - 1 , 1 )	, new Date( now.getFullYear(), now.getMonth(), 0 ) ]
		};
		if ( map[type] === undefined ) { return; }
		const fr = {
			"y" : map[type][0].getFullYear(),
			"m" : String( map[type][0].getMonth() + 1 ).padStart(2, "0"),
			"d" : String( map[type][0].getDate()      ).padStart(2, "0"),
		};
		const to = {
			"y" : map[type][1].getFullYear(),
			"m" : String( map[type][1].getMonth() + 1 ).padStart(2, "0"),
			"d" : String( map[type][1].getDate()      ).padStart(2, "0"),
		};
		if ( type == "init" ) {
			if ( !vue_data.param['fr_date'] && !vue_data.param['to_date'] ) {
				vue_data.param['to_date'] = `${to.y}-${to.m}-${to.d}`;
			}
			return;
		}
		vue_data.param['fr_date'] = `${fr.y}-${fr.m}-${fr.d}`;
		vue_data.param['to_date'] = `${to.y}-${to.m}-${to.d}`;
	}


	// reqGet( "/lim/lab/ajax_simulator.php", { market : "coupang" }, callback );
	// callback = resp_data => {
	// 	if ( !resp_data || resp_data.code != 200 ) { return; }
	// }
	reqGet = async function ( url_path, param_obj, callback ) {
		let params_str = new URLSearchParams(param_obj).toString();
		if ( params_str ) { params_str = `?${params_str}` }
		await fetch( `${url_path}${params_str}` ).then(response => response.json()).then(callback).catch(error => console.log(error));
		console.log('reqAsync');
	}

	reqPost = async function ( url_path, param_obj, callback ) {
		const post_conf = {
			"method": "POST",
			"headers": {
				"content-type": "application/json"
				// "content-type": "application/x-www-form-urlencoded"
			},
			"body": JSON.stringify( param_obj )
			// "body": new URLSearchParams( param_obj ).toString()
		}
		await fetch( url_path, post_conf ).then(response => response.json()).then(callback).catch(error => console.log(error));
		console.log('reqAsync');
	}

}
let tools = new Tools("John");
// tools.debug_flag = true;




let vue_data = {};

const searcher = {
	open() {
		// some code
	},
	pageTo( page ) {
		// some code
	}
}

Vue.createApp({
	data() {
		return {
			vue_data : {
				items : {},
				param : { page: 1 }
			},
			searcher : searcher,

			tools : tools,
			outfn : {
				number_format : number_format
			}
		}
	},
	mounted() {
		vue_data = this.vue_data;
		searcher.open();
	},
	methods: {}
}).mount("#vue");



