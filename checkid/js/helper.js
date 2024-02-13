Handlebars.registerHelper('moduleAuth', function(data, text) {
	if(data.useVerifyExt){
	    return Handlebars.templates['pages/pfingerprintauth_capture']({data: data, text: text});
	}else{
	    return Handlebars.templates['pages/pfingerprintauth_verify']({data: data, text: text});
	}
});
Handlebars.registerHelper('if_eq', function(a, b, opts) {
	if (a == b) {
        return opts.fn(this);
    } else {
        return opts.inverse(this);
    }
});