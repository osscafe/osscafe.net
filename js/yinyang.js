((function(){var a,b,c=function(a,b){return function(){return a.apply(b,arguments)}},d=Object.prototype.hasOwnProperty,e=function(a,b){function e(){this.constructor=a}for(var c in b)d.call(b,c)&&(a[c]=b[c]);e.prototype=b.prototype;a.prototype=new e;a.__super__=b.prototype;return a};a=function(){function a(){this.build=c(this.build,this);this.setup()}a.version="0.2.2";a.plugins={};a.filters={};a.templates={};a.createFilter=function(b){var c,d,e;d=b.split(":");e=d.shift();d=function(){var a,b,e;e=[];for(a=0,b=d.length;a<b;a++){c=d[a];c.match(/^[1-9][0-9]*$/)?e.push(Number(c)):e.push(c.replace(/^\s*('|")|("|')\s*$/g,""))}return e}();return a.filters[e]!=null?new a.filters[e](d):new a.filter(d)};a.getTemplate=function(b){return a.templates[b]!=null?a.templates[b]:null};a.createTemplate=function(b,c){var d;d=b.replace(/[^\/]+$/,"");c=c.replace(/(href|src)="((?![a-z]+:\/\/|\.\/|\/|\#).*?)"/g,function(){return""+arguments[1]+'="'+d+arguments[2]+'" '});return this.templates[b]=new a.Template(c)};a.guid=function(){return"xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g,function(a){var b,c;b=Math.random()*16|0;c=a==="x"?b:b&3|8;return c.toString(16)}).toUpperCase()};a.prototype.template=null;a.prototype.document_meta={};a.prototype.selfload=!1;a.prototype.setup=function(){var a,b,c,d,e,f;e=$("meta");f=[];for(c=0,d=e.length;c<d;c++){a=e[c];if($(a).attr("content")==null)continue;b=$(a).attr("name")||$(a).attr("property");if(b==="yinyang:selfload"&&$(a).attr("content")==="true")f.push(this.selfload=!0);else{b=b.replace(/[^a-zA-Z0-9_]/g,"_");f.push(this.document_meta[b]=$(a).attr("content"))}}return f};a.prototype.fetch=function(a){var b=this;this.selfload&&this.redrawAll(this.build(location.href,$("html").html().replace(/#%7B(.*?)%7D/gm,"#{$1}")));if(a)return $.ajax({url:a,success:function(c){return b.redrawAll(b.build(a,c))}})};a.prototype.build=function(b,c){this.template=a.createTemplate(b,c);return this.template.display(this)};a.prototype.redrawAll=function(a){var b,c,d,e,f;a=a.replace(/<script.*?src=".*?yinyang.js".*?><\/script>/gim,"");$("body").html(a.split(/<body.*?>|<\/body>/ig)[1]);$("head").html(a.split(/<head.*?>|<\/head>/ig)[1]);e=$(a.match(/<body.*?>/i)[0].replace(/^\<body/i,"<div"))[0].attributes;f=[];for(c=0,d=e.length;c<d;c++){b=e[c];b.name==="class"?f.push($("body").addClass(b.value)):b.value&&b.value!=="null"?f.push($("body").attr(b.name,b.value)):f.push(void 0)}return f};return a}();a.filter=function(){function a(a){this.args=a}a.prototype._process=function(a){switch(this.args.length){case 0:return this.process(a);case 1:return this.process(a,this.args[0]);case 2:return this.process(a,this.args[0],this.args[1]);default:return this.process(a,this.args[0],this.args[1],this.args[2])}};a.prototype.process=function(a){return a};return a}();$("head").append("<style>body {background:#FFF} body * {display:none}</style>");$(function(){var b,c;b=$("link[rel=template]").attr("href");c=new a;return c.fetch(b)});if(!b){b=function(a,c,d){var e,f,g,h,i,j,k;if(Object.prototype.toString.call(c)!=="[object RegExp]")return b._nativeSplit.call(a,c,d);j=[];g=0;e=(c.ignoreCase?"i":"")+(c.multiline?"m":"")+(c.sticky?"y":"");c=RegExp(c.source,e+"g");a+="";b._compliantExecNpcg||(k=RegExp("^"+c.source+"$(?!\\s)",e));if(d==null||+d<0)d=Infinity;else{d=Math.floor(+d);if(!d)return[]}while(i=c.exec(a)){f=i.index+i[0].length;if(f>g){j.push(a.slice(g,i.index));!b._compliantExecNpcg&&i.length>1&&i[0].replace(k,function(){var a,b,c;c=[];for(a=1,b=arguments.length-2;1<=b?a<=b:a>=b;1<=b?a++:a--)arguments[a]==null&&c.push(i[a]=void 0);return c});i.length>1&&i.index<a.length&&Array.prototype.push.apply(j,i.slice(1));h=i[0].length;g=f;if(j.length>=d)break}c.lastIndex===i.index&&c.lastIndex++}g===a.length?(h||!c.test(""))&&j.push(""):j.push(a.slice(g));return j.length>d?j.slice(0,d):j};b._compliantExecNpcg=/()??/.exec("")[1]!=null;b._nativeSplit=String.prototype.split}String.prototype.split=function(a,c){return b(this,a,c)};a.plugins.ajax=function(a,b,c){typeof console!="undefined"&&console!==null&&console.log("ajax request : "+c);return $.getJSON(c).success(function(c){a.setValue(b,c);return a.processPlaceholder(b)}).error(function(){return typeof console!="undefined"&&console!==null?console.log("ajax error"):void 0})};a.plugins.hsql=function(a,b,c){typeof console!="undefined"&&console!==null&&console.log("hsql request : "+c);return $.getJSON("/hsql.php?q="+c).success(function(c){a.setValue(b,c);return a.processPlaceholder(b)}).error(function(){return typeof console!="undefined"&&console!==null?console.log("hsql error"):void 0})};a.filters["default"]=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.process=function(a,b){b==null&&(b="");return a||b};return b}(a.filter);a.filters.nl2br=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.process=function(a){return a.replace(/\r\n|\n|\r/gim,"<br />")};return b}(a.filter);a.filters.truncate=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.process=function(a,b,c){b==null&&(b=80);c==null&&(c="...");return a.length>b?a.substring(0,b-c.length)+c:a};return b}(a.filter);a.filters.beforetag=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.process=function(a,b){b==null&&(b="hr");return a.split(/(<hr.*?>)/im)[0]};b.prototype.process=function(a,b){b==null&&(b="hr");return a.split(new RegExp("(<"+b+".*?>)","im"))[0]};return b}(a.filter);a.filters.aftertag=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.process=function(a,b){b==null&&(b="hr");return a.split(new RegExp("(<"+b+".*?>)","im"))[2]};return b}(a.filter);a.Template=function(){function b(b){var c,d,e,f,g,h,i,j,k,l,m,n,o,p,q;i=function(){var b,c;b=a.plugins;c=[];for(f in b){g=b[f];c.push(f)}return c}().join("|");p=b.match(new RegExp('<meta.*? name="('+i+')\\.[a-z][a-zA-Z0-9_\\.]+".*?>',"gim"))||[];for(l=0,n=p.length;l<n;l++){e=p[l];k=$(e).attr("name");h=k.split(".")[0];c=$(e).attr("content");a.plugins[h](this,k,c)}j=this.root=new a.TemplateRoot(this);q=b.split(/(<!--\{.+?\}-->|\#\{.+?\})/gim);for(m=0,o=q.length;m<o;m++){d=q[m];d!=null&&(j=j.add(d))}}b.prototype.values={meta:{},ajax:{},hsql:{}};b.prototype.placeholders={};b.prototype.root=null;b.prototype.display=function(a){this.values.meta=a.document_meta;return this.root.display()};b.prototype.valueExists=function(a){var b,c,d,e;c=a.split(".");d=this.values;while(d!=null&&(b=c.shift()))d=(e=d[b])!=null?e:null;return d!=null};b.prototype.setValue=function(a,b){var c,d,e,f,g;d=a.split(".");e=d.pop();f=this.values;while(c=d.shift())f=(g=f[c])!=null?g:"";return f[e]=b};b.prototype.setValues=function(a){var b,c,e;e=[];for(b in a){if(!d.call(a,b))continue;c=a[b];e.push(this.values[b]=c)}return e};b.prototype.getValue=function(a){var b,c,d,e;c=a.split(".");d=this.values;while(b=c.shift())d=(e=d[b])!=null?e:"";return d};b.prototype.addPlaceholder=function(a,b){return this.placeholders[a]=b};b.prototype.processPlaceholder=function(a){if(this.placeholders[a]!=null){this.placeholders[a]();return delete this.placeholders[a]}};return b}();a.TemplateRoot=function(){function b(a,b,c,d){this.template=a;this.parent=b!=null?b:null;this.value=c!=null?c:"";this.ignore=d!=null?d:!1;this.children=[]}b.prototype.add=function(b){var c;c={pend:/<!--\{end\}-->/,more:/<!--\{more\}-->/,pvar:/<!--\{(@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)(\|.*?)*\}-->/,ivar:/\#\{(@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)(\|.*?)*\}/,loop:/<!--\{[a-zA-Z][a-zA-Z0-9_\.]* in (@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)\}-->/};if(b.match(c.pend)){this.ignore=!1;return this.parent}if(b.match(c.more)){this.ignore=!0;return this}return this.ignore?this:b.match(c.pvar)?this._add("child",new a.TemplateVar(this.template,this,b.replace(/<!--{|}-->/g,""),!0)):b.match(c.ivar)?this._add("self",new a.TemplateVar(this.template,this,b.replace(/\#\{|\}/g,""))):b.match(c.loop)?this._add("child",new a.TemplateLoop(this.template,this,b.replace(/<!--{|}-->/g,""))):this._add("self",new a.TemplateText(this.template,this,b))};b.prototype._add=function(a,b){this.children.push(b);switch(a){case"child":return b;case"self":return this}};b.prototype.display=function(a){var b;a==null&&(a={});return function(){var c,d,e,f;e=this.children;f=[];for(c=0,d=e.length;c<d;c++){b=e[c];f.push(b.display(a))}return f}.call(this).join("")};return b}();a.TemplateLoop=function(b){function c(){c.__super__.constructor.apply(this,arguments)}e(c,b);c.prototype.display=function(b){var c,d,e;this.placeholder_id=a.guid();e=this.value.split(/\s+in\s+/),d=e[0],c=e[1];if(this.template.valueExists(c))return this.displayLoop(b,d,c);if(c.match(/^(ajax|hsql)\./))return this.diaplayPlaceholder(b,d,c);typeof console!="undefined"&&console!==null&&console.log("Template value not found.");return""};c.prototype.displayLoop=function(a,b,c){var d,e,f,g,h;return function(){var i,j,k,l;k=this.template.getValue(c);l=[];for(i=0,j=k.length;i<j;i++){e=k[i];l.push(function(){var c,i,j,k;j=this.children;k=[];for(c=0,i=j.length;c<i;c++){d=j[c];g={};for(f in a){h=a[f];g[f]=h}g[b]=e;k.push(d.display(g))}return k}.call(this).join(""))}return l}.call(this).join("")};c.prototype.diaplayPlaceholder=function(a,b,c){var d=this;this.template.addPlaceholder(c,function(){var e;e=d.displayLoop(a,b,c);return $("#"+d.placeholder_id).before(e).remove()});return'<span class="loading" id="'+this.placeholder_id+'"></span>'};return c}(a.TemplateRoot);a.TemplateVar=function(b){function c(b,c,d,e){var f,g;this.template=b;this.parent=c!=null?c:null;this.value=d!=null?d:"";this.ignore=e!=null?e:!1;g=this.value.split("|");this.value=g.shift();this.filters=function(){var b,c,d;d=[];for(b=0,c=g.length;b<c;b++){f=g[b];d.push(a.createFilter(f))}return d}();this.children=[]}e(c,b);c.prototype.display=function(a){var b,c,d,e,f;this.localValues=a;c=this.value.substring(0,1)==="@"?this.displayDom():this.displayVar();f=this.filters;for(d=0,e=f.length;d<e;d++){b=f[d];c=b._process(c)}return c};c.prototype.displayDom=function(){return $(this.value.substring(1)).html()};c.prototype.displayVar=function(){return this.getLocalValue(this.value)||this.template.getValue(this.value)};c.prototype.getLocalValue=function(a){var b,c,d,e;c=a.split(".");d=this.localValues;while(b=c.shift())d=(e=d[b])!=null?e:"";return d};return c}(a.TemplateRoot);a.TemplateText=function(a){function b(){b.__super__.constructor.apply(this,arguments)}e(b,a);b.prototype.display=function(){return this.value};return b}(a.TemplateRoot)})).call(this);