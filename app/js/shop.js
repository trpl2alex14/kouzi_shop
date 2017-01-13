KouziShop = {
    url : '', 
    shopWrapper : {
        idblock : '',
        callbk: null        
    },
    
    f_ajaxtmpl:function(tmpl, callbk){        
        jQuery.ajax({type: "POST",
                url: KouziShop.url+"route.php?form="+tmpl, 
                dataType: "json",
                success: function(data){                                                                                                                                
                        switch(data.status){
                            case 'success':	
                                callbk(data);
                            break;	
                        }                        
                }                 
        });                        
    },

    load:function(configForm, tmpl){
        var config = configForm;
        KouziShop.f_ajaxtmpl(tmpl,function(responseData){
                    jQuery(config.idblock).append(responseData.data);                    
                    if (typeof config.callbk === 'function') {
                        config.callbk();
                    }
        });
    },    
    
    init:function(wrapper){
        this.shopWrapper.idblock = wrapper;
        this.load(this.shopWrapper,"index");
    }
};

KouziCatalog = {
        catalog : [               
            {
                "id"   : 1,
                "name" : "КОУЗИ",                
                "sale" : 5200
            }        
        ],
        
        load:function(){
            
        }
}


