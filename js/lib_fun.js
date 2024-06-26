

// like php $_GET
let js_GET = {};
initJs_GET();
function initJs_GET() {
	for(var [k1, v1] of new URLSearchParams( new URL(location).search ).entries()) {
		js_GET[k1] = v1;
	}
}



class Tools {

	debug_flag = false;	// true 디버깅, false 노 디버깅
	constructor() {}

	/**
	 * php sleep 함수와 같은 기능
	 * @param {int} time 
	 * @returns void
	 * 
	 * 예시 :
	 * fn();
	 * async function fn() {
	 *     console.log("one");
	 *     await tools.delayer( 2000 );
	 *     console.log("two");
	 * }
	 */
	delayer = (time) => new Promise( (resolve)=>setTimeout( ()=>resolve(), time ) );

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


	numberFormat = function ( number, Currency ) {
		let result;
		if ( Currency === "CNY" ) {
			result = new Intl.NumberFormat('zh-CN', { style: 'currency', currency: 'CNY' });
		} else if ( Currency === "KRW" ) {
			result = new Intl.NumberFormat("ko-KR", { style: "currency", currency: "KRW" });
		} else {
			result = new Intl.NumberFormat();
		}
		result = result.format( new Number(number) );
		return result === "NaN" ? "" : result;
	}

	getExcelAtoZIndex = function ( number ) {
		let base26_arr = Array.from( parseInt(number).toString(26) );

		for (let [k1, v1] of Object.entries( base26_arr )) {
			v1 = parseInt( v1, 26 ) + 10;
			if ( k1 === "0" && base26_arr.length > 1 ) { v1 --; }
			base26_arr[k1] = parseInt(v1).toString(36).toUpperCase();
		}

		return base26_arr.join("");
	}

	setSearchDate = function ( type ) {
		const aday		= 1000 * 60 * 60 * 24;
		const now		= new Date();	// new Date() - aday * 2
		const tod		= new Date( now.getFullYear(), now.getMonth(), now.getDate() );
		const map_week	= [ 6, 0, 1, 2, 3, 4, 5 ];
		const week_i	= map_week[ now.getDay() ];
		const map = {
			"init"			: [ new Date( now.getFullYear(), now.getMonth() - 1 , now.getDate() + 1 )	, now ],
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
				// vue_data.param['fr_date'] = `${fr.y}-${fr.m}-${fr.d}`;
				vue_data.param['to_date'] = `${to.y}-${to.m}-${to.d}`;
			}
			return;
		}
		vue_data.param['fr_date'] = `${fr.y}-${fr.m}-${fr.d}`;
		vue_data.param['to_date'] = `${to.y}-${to.m}-${to.d}`;
	}

	timestampToDateStr = function ( timestamp ) {
		var timeobj = new Date(timestamp);

		let month = timeobj.getMonth() + 1;
		let day = timeobj.getDate();
		let hour = timeobj.getHours();
		let minute = timeobj.getMinutes();
		let second = timeobj.getSeconds();

		month = month >= 10 ? month : '0' + month;
		day = day >= 10 ? day : '0' + day;
		hour = hour >= 10 ? hour : '0' + hour;
		minute = minute >= 10 ? minute : '0' + minute;
		second = second >= 10 ? second : '0' + second;

		return timeobj.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
	}

	secondToTimeStr = function ( total_time ) {
		total_time /= 1000;
		var days = Math.floor(total_time/86400); 
		var time = total_time - (days*86400); 
		var hours = Math.floor(time/3600); 
		time = time - (hours*3600); 
		var min = Math.floor(time/60); 
		var sec = time - (min*60); 
		
		var str = '';
		if(days==0&&hours==0&&min==0) {
			str = `${sec}초`;
		} else if (days==0&&hours==0) {
			str = `${min}분 ${sec}초`;
		} else if (days==0) {
			str = `${hours}시간 ${min}분 ${sec}초`;
		} else {
			str = `${days}일 ${hours}시간 ${min}분 ${sec}초`;
		}
		return str;
	}

	/**
	 * var mb_id_js = "<?=$mb_id;?>";
	 * tools.isUserGroupJs();
	 * if ( tools.isUserGroupJs("master") ) {}
	 */
	isUserGroupJs(line) {
        if ( !window.mb_id_js ) { return false; }
		if ( line === "master" ) {
			return false
				|| mb_id_js === "doc2327"
				|| false;
		}
		if ( line === "admin" ) {
			return false
				|| mb_id_js === "doc2327"
				|| mb_id_js === "harlemboy"
				|| false;
		}
		if ( line === "opt_edit" ) {
			return false
				|| mb_id_js === "doc2327"
				|| false;
		}
		return false;
	}


	// await tools.fetchGet( "/lim/lab/ajax_simulator.php", { market : "COUPANG" } ).then(response => response.json()).then(resp_data => {
	// 	if ( resp_data?.status !== 1 ) { return; }
	// })
	fetchGet = function ( url_path, param_obj ) {
		let params_str = new URLSearchParams(param_obj).toString();
		if ( params_str ) { params_str = `?${params_str}` }
		return fetch( `${url_path}${params_str}` ).catch(error => { console.log(error); return Promise.reject(); });
	}

	// tools.fetchPost( "/lim/lab/ajax_simulator.php", { market : "COUPANG" } )										// $file_input
	// tools.fetchPost( "/lim/lab/ajax_simulator.php", { market : "COUPANG" }, "urlencoded" )						// $_POST(1 depth)
	// tools.fetchPost( "/lim/lab/ajax_simulator.php", new FormData().append("market", "COUPANG"), "form_data" )	// $_POST
	// tools.fetchPost( "/lim/lab/ajax_simulator.php", new FormData(jq_form[0]), "form_data" )						// $_POST, $_FILES
	// .then(response => response.json()).then(resp_data => {
	//     if ( resp_data?.status !== 1 ) { return; }
	// })
	fetchPost = function ( url_path, data, mode = "json" ) {
		const post_conf = {"method": "POST"};
		if ( mode === "json" ) {
			post_conf.headers = {"content-type": "application/json"};
			post_conf.body = JSON.stringify( data );	// {}
		} else if ( mode === "urlencoded" ) {
			post_conf.headers = {"content-type": "application/x-www-form-urlencoded"};
			post_conf.body = new URLSearchParams( data ).toString();	// {}
		} else {
			// post_conf.headers = {"content-type": "multipart/form-data; boundary=----"};
			post_conf.body = data;	// new FormData(el_form)
		}
		return fetch( url_path, post_conf ).catch(error => { console.log(error); return Promise.reject(); });
	}

	// 로그인 페이지로 이동, 로그인 즉시 전 페이지로 이동
	login = function () {
		location.href = '/main/login.php?url=' + encodeURIComponent( new URL(location).pathname + new URL(location).search );
	}

	loadingBtn = function (el_icon) {
		$(el_icon).html(/*html*/`<img id="loading-image" src="/img/gif-load.gif" alt="Loading..." style="height:15px" />`);
		$("input, button").attr("disabled", true);
	}

	exampleLoop = function () {
		let arr = [10, 11, 12, 13, 14, 15];
		arr.__proto__[6] = 16;
		let obj = { "a" : 10, "b": 11, "c": 12, "d": 13, "e": 14, "f": 15 };
		obj.__proto__.g = 16;

		console.log("* for normal :");
		for (let k1 = 0; k1 < arr.length; k1++) {
			const v1 = arr[k1];
			if ( k1 === 2 ) { continue; }
			// if ( k1 === 4 ) { break; }
			console.log(k1, v1);
		}

		console.log("* for each :");
		arr.forEach((v1, k1) => {
			if ( k1 === 2 ) { return; }	// continue
			console.log(k1, v1);
		});

		console.log("* for in arr :");
		for (const k1 in arr) {
			// if (!Object.hasOwnProperty.call(arr, k1)) { continue; }
			const v1 = arr[k1];
			if ( k1 === "2" ) { continue; }
			// if ( k1 === "4" ) { break; }
			console.log(k1, v1);
		}
		console.log("* for in obj :");
		for (const k1 in obj) {
			// if (!Object.hasOwnProperty.call(obj, k1)) { continue; }
			const v1 = obj[k1];
			if ( k1 === "c" ) { continue; }
			// if ( k1 === "e" ) { break; }
			console.log(k1, v1);
		}

		console.log("* for of arr :");
		for (const [k1, v1] of Object.entries(arr)) {
			if ( k1 === "2" ) { continue; }
			// if ( k1 === "4" ) { break; }
			console.log(k1, v1);
		}
		console.log("* for of obj :");
		for (const [k1, v1] of Object.entries(obj)) {
			if ( k1 === "c" ) { continue; }
			// if ( k1 === "e" ) { break; }
			console.log(k1, v1);
		}

		if ( $.each ) {
			console.log("* jquery each dom");
			$("span").each((k1, el) => {
				if ( k1 === 2 ) { return true; }	// continue
				if ( k1 === 4 ) { return false; }	// break
				console.log(k1, el);
			})
			console.log("* jquery each arr");
			$.each(arr, (k1, v1) => {
				if (!Object.hasOwnProperty.call(arr, k1)) { return; }
				if ( k1 === 2 ) { return true; }	// continue
				// if ( k1 === 4 ) { return false; }	// break
				console.log(k1, v1);
			})
			console.log("* jquery each obj");
			$.each(obj, (k1, v1) => {
				if (!Object.hasOwnProperty.call(obj, k1)) { return; }
				if ( k1 === "c" ) { return true; }	// continue
				// if ( k1 === "e" ) { return false; }	// break
				console.log(k1, v1);
			})
		}
	}

}
let tools = new Tools("John");
// tools.debug_flag = true;







const vue_component = {

	/**
	 * <comp_pg
	 * 	:page="14"
	 * 	:page_cnt_total="487"
	 * 	:page_start="11"
	 * 	:page_end="20"
	 * 	:pageto="vue_method.pageTo"
	 * ></comp_pg>
	 */
	pg : {
		name: 'pg',
		props: ['page_cnt_total', 'page_start', 'page_end', 'page', 'pageto'],
		template: /*html*/`
			<div class="paging-wrap" v-if="page_cnt_total > 1" v-cloak>
				<nav class="pg_wrap">
					<span class="pg">
						<a href="javascript:;" @click="pageto(1)" v-if="page > 1" class="pg_page pg_start">처음</a>
						<a href="javascript:;" @click="pageto( page_start - 1 )" v-if="page_start > 1" class="pg_page pg_prev">이전</a>
						<template v-for="(i, n) in page_end - page_start + 1">
							<strong v-if="n === page - page_start" class="pg_current"><span class="sound_only">열린</span>{{page_start + n}}<span class="sound_only">페이지</span></strong>
							<a href="javascript:;" @click="pageto( page_start + n )" v-else="" class="pg_page">{{page_start + n}}<span class="sound_only">페이지</span></a>
						</template>
						<a href="javascript:;" @click="pageto( page_end + 1 )" v-if="page_end < page_cnt_total" class="pg_page pg_next">다음</a>
						<a href="javascript:;" @click="pageto( page_cnt_total )" v-if="page < page_cnt_total" class="pg_page pg_end">맨끝</a>
					</span>
				</nav>
				<div class="clr"></div>
			</div>
		`
	},

	/**
	 * <ldio_01 :ldio_text="vue_data.conf.ldio_text"></ldio_01>
	 * ldio_text = "some text" / undefined
	 */
	ldio_01 : {
		name: 'ldio_01',
		props: ['ldio_text'],
		template: /*html*/`
			<div class="full-screen-wrap" v-show="ldio_text !== undefined">
				<div class="contents-wrap">
					<div class="loadingio-spinner-wedges-4gilftv1pz1">
						<div class="ldio-hhnfgr120pp">
							<div>
								<div><div></div></div>
								<div><div></div></div>
								<div><div></div></div>
								<div><div></div></div>
							</div>
						</div>
					</div>
					<h3 v-html="ldio_text"></h3>
				</div>
			</div>
		`
	},

};





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
	components: {
		vuedraggable,
		comp_pg: vue_component.pg,
	},
	data() {
		return {
			vue_data		: vue_data,
			vue_method		: vue_method,

			tools			: tools,
			ezy_q			: ezy_q,
			dpixel_001		: dpixel_001,
			outfn : {
				number_format : number_format
			}
		}
	},
	mounted() {
		vue_data		= this.vue_data;
		vue_method		= this.vue_method;
		vue_data.$refs	= this.$refs;

		vue_method.open();
		vue_method.modalInit();
	},
	methods: {},
    computed: {}
}).mount("#vue-someid");



