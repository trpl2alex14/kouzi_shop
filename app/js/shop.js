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
                        config.callbk(responseData);
                    }
        });
    },    
    
    init:function(wrapper){
        this.shopWrapper.idblock = wrapper;
        this.shopWrapper.callbk = KouziCatalog.load;
        this.load(this.shopWrapper,"index");
    }
};
   

KouziCatalog = {
        tpl_item : '<div class="item">\n\
                    <h4>{name}</h4>\n\
                    <img src="image/{img}">\n\
                    <p>{info}</p>\n\
                    <div class="price">{price} руб.</div> \n\
                    <div class="action-block">\n\
                    <a class="btn btn-add" onclick="KouziCatalog.addArticle({id})">В корзину</a>\n\
                    <a class="btn about" onclick="KouziCatalog.infoArticle({id})">Подробнее</a>\n\
                    </div>\n\
                    </div>',
        catalog : null,
        
        load:function(responseData){
            KouziCatalog.catalog = responseData.array;                      
            jQuery('#catalog-article').html('');
            KouziCatalog.catalog.forEach(function(item) {
                            var tmp = KouziCatalog.tpl_item;                            
                            for (var key in item){                                  
                                tmp = tmp.replace('{'+key+'}',item[key]);                 
                            }
                            jQuery('#catalog-article').append(tmp);            
            });
        },
        
        addArticle: function(id){
            console.log(id);
        },
        
        infoArticle: function(id){
            console.log(id);
        }        
};


