/*! Case - v1.2.1 - 2015-01-29
* Copyright (c) 2015 Nathan Bubna; Licensed MIT, GPL */
(function(){"use strict";var a=function(a,b){return b=b||"",a.replace(/(^|-)/g,"$1\\u"+b).replace(/,/g,"\\u"+b)},b=a("20-2F,3A-40,5B-60,7B-7E,A0-BF,D7,F7","00"),c="a-z"+a("DF-F6,F8-FF","00"),d="A-Z"+a("C0-D6,D8-DE","00"),e="A|An|And|As|At|But|By|En|For|If|In|Of|On|Or|The|To|Vs?\\.?|Via",f=function(a,f,g,h){return a=a||b,f=f||c,g=g||d,h=h||e,{capitalize:new RegExp("(^|["+a+"])(["+f+"])","g"),pascal:new RegExp("(^|["+a+"])+(["+f+g+"])","g"),fill:new RegExp("["+a+"]+(.|$)","g"),sentence:new RegExp('(^\\s*|[\\?\\!\\.]+"?\\s+"?|,\\s+")(['+f+"])","g"),improper:new RegExp("\\b("+h+")\\b","g"),relax:new RegExp("([^"+g+"])(["+g+"]*)(["+g+"])(?=["+f+"]|$)","g"),upper:new RegExp("^[^"+f+"]+$"),hole:/\s/,room:new RegExp("["+a+"]")}},g=f(),h={re:g,unicodes:a,regexps:f,types:[],up:String.prototype.toUpperCase,low:String.prototype.toLowerCase,cap:function(a){return h.up.call(a.charAt(0))+a.slice(1)},decap:function(a){return h.low.call(a.charAt(0))+a.slice(1)},fill:function(a,b){return a&&null!=b?a.replace(g.fill,function(a,c){return c?b+c:""}):a},prep:function(a,b,c,d){return a?(!d&&g.upper.test(a)&&(a=h.low.call(a)),b||g.hole.test(a)||(a=h.fill(a," ")),c||g.room.test(a)||(a=a.replace(g.relax,h.relax)),a):a||""},relax:function(a,b,c,d){return b+" "+(c?c+" ":"")+d}},i={_:h,of:function(a){for(var b=0,c=h.types.length;c>b;b++)if(i[h.types[b]](a)===a)return h.types[b]},flip:function(a){return a.replace(/\w/g,function(a){return a==h.up.call(a)?h.low.call(a):h.up.call(a)})},type:function(a,b){i[a]=b,h.types.push(a)}},j={snake:function(a){return i.lower(a,"_")},constant:function(a){return i.upper(a,"_")},camel:function(a){return h.decap(i.pascal(a))},lower:function(a,b){return h.fill(h.low.call(h.prep(a,b)),b)},upper:function(a,b){return h.fill(h.up.call(h.prep(a,b,!1,!0)),b)},capital:function(a,b){return h.fill(h.prep(a).replace(g.capitalize,function(a,b,c){return b+h.up.call(c)}),b)},pascal:function(a){return h.fill(h.prep(a,!1,!0).replace(g.pascal,function(a,b,c){return h.up.call(c)}),"")},title:function(a){return i.capital(a).replace(g.improper,function(a){return h.low.call(a)})},sentence:function(a,b){return a=i.lower(a).replace(g.sentence,function(a,b,c){return b+h.up.call(c)}),b&&b.forEach(function(b){a=a.replace(new RegExp("\\b"+i.lower(b)+"\\b","g"),h.cap)}),a}};j.squish=j.pascal;for(var k in j)i.type(k,j[k]);var l="function"==typeof l?l:function(){};l("object"==typeof module&&module.exports?module.exports=i:this.Case=i)}).call(this);