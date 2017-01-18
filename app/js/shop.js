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
    
    ready:function(responseData){
        KouziCatalog.load(responseData);
        KouziList.load(responseData);
    },
    
    init:function(wrapper){
        this.shopWrapper.idblock = wrapper;
        this.shopWrapper.callbk = this.ready;
        this.load(this.shopWrapper,"index");
    },
    
    sendList: function(){
        //send  list article
    },
    
    nextStep: function(step){
        if(step === 1){
            this.sendList();
            ///
        }
       ///
    }
};
   

KouziCatalog = {
        tpl_item : '<div class="item">\n\
                    <h4>{name}</h4>\n\
                    <img src="image/{img}">\n\
                    <p>{info}</p>\n\
                    <div class="price">{price} руб.</div> \n\
                    <div class="action-block">\n\
                    <a class="btn btn-add" onclick="KouziCatalog.addArticle({id});">В корзину</a>\n\
                    <a class="btn about" onclick="KouziCatalog.infoArticle({id});">Подробнее</a>\n\
                    </div>\n\
                    </div>',
        
        catalog : null,
        
        load:function(responseData){
            KouziCatalog.catalog = responseData.array;                      
            jQuery('#catalog-article').html('');
            KouziCatalog.catalog.forEach(function(item) {
                        if(item['type']==='d' || item['type']==='v'){
                            var tmp = KouziCatalog.tpl_item;                            
                            for (var key in item){                                  
                                tmp = tmp.replace(new RegExp('{'+key+'}','g'),item[key]);                 
                            }
                            jQuery('#catalog-article').append(tmp);      
                        }
            });
        },
        
        addArticle: function(id){
            var art = this.getItem(id);
            if(art){
                var components = {  config : {  
                                        type    :'config',   
                                        articulInit : art.articul,
                                        articul : 0,
                                        count   : 1                                        
                                    },
                                    line1  : {
                                        type:'text',
                                        data:'<h3>'+art.name+'</h3><p><img src="image/'+art.img+'"></p>'
                                    }                                   
                                 };
                if(art.type==='d' && art.model){
                    for(var i=0;i<art.model.length;i++){                        
                        if(art.model[i].type){
                            components['line'+Number(i+2)] = {
                                type : 'checkbox',
                                data : '<h3>'+art.model[i].name+'</h3>',
                                value: art.model[i].item[0].atrmod,
                                item : art.model[i].item
                            };
                        }
                    }
                }
                
                components['count'] = {
                                        type:'count',
                                        data:'Укажите колличество',
                                        value:1
                }; 
                
                KouziModal.create(components,function(params){
                            KouziList.add(params.config.articul,params.count.value);                    
                });
            }
        },
        
        infoArticle: function(id){
            var art = this.getItem(id);
            if(art && art.about){
                KouziModal.showModalInfo(art.about);
            }
        },    
        
        getItem:function(articul){
            for(var i=0;i<KouziCatalog.catalog.length;i++){
                if(KouziCatalog.catalog[i].articul===articul){
                    return KouziCatalog.catalog[i];
                }
            }
            return null;
        }
};


KouziModal = {
    callbk:null,
    
    components : null,
    
    create:function(components,callbk){
        $('#content_modal').html('');
        if(components){            
            this.components = components;
            var item;
            for(var key in this.components){
                item = this.components[key];
                switch (item.type){
                    case 'text':
                        $('#content_modal').append(item.data);
                    break;           
                    case 'count':
                        $('#content_modal').append('<input type="number" size="2" name="count" min="1" max="99" value="1">'+item.data);
                    break;
                    case 'checkbox':
                        $('#content_modal').append(item.data);
                        for(var j=0;j<item.item.length;j++){
                            $('#content_modal').append('<input type="radio" name="'+key+'" value="'+item.item[j].artmod+'" '+( j===0 ? 'checked':'')+'>'+item.item[j].text+'<br>');
                        }                                                
                    break;         
                ///color
                }                
            }
        }
        $('#content_modal input').change(function(){KouziModal.viewPrice.call(KouziModal);});
        this.viewPrice();
        this.callbk = callbk;        
        $('.fixed-overlay .action-block .btn').hide();
        $('.fixed-overlay .action-block .cancel').show();        
        $('.fixed-overlay .action-block .btn-add').show();        
        $('.fixed-overlay').show();
    },
    
    viewPrice:function(){
        var param = this.getParams();
        var art = KouziCatalog.getItem(param.config.articul);
        if(art){
            $('.price-box span').html(art.price);
        }
    },
    
    showModalInfo: function(content){
                $('#content_modal').html(content);
                $('.fixed-overlay').show();
                $('.fixed-overlay .action-block .btn').hide();
                $('.fixed-overlay .action-block .about').show();        
    },
    
    getParams:function(){
        var components;
        if(this.components){
            var item;
            var art_mod = 0;
            components = this.components;
            for(var key in components){
                item = components[key];
                switch (item.type){           
                    case 'count':
                        components[key].value = Number(jQuery('#content_modal input[name=count]').val());                                  
                    break;   
                    case 'checkbox':
                        components[key].value = Number(jQuery('#content_modal input[name='+key+']:checked').val());                        
                    break;                
                ///color
                }
                if(item.value && item.type!=='count'){
                    art_mod+=item.value;
                }
            }
            components.config.articul=art_mod+components.config.articulInit;
        }
        return components;
    },
    
    applayBtn:function(){
        this.callbk(this.getParams());
        this.aboutBtn();
    },
    
    aboutBtn:function(){                
                $('.fixed-overlay .action-block .btn').show();                
                $('.fixed-overlay').hide();
    },
    
    cancelBtn:function(){                
                this.aboutBtn();
    }     
};

KouziList = {
    tpl_item:'<div class="line">\n\
                <div class="col number">{ind}.</div>\n\
                <div class="col name">{name}</div>\n\
                <div class="col price">{price} руб.</div>\n\
                <div class="col count">{count}</div>\n\
                <div class="col total">{total} руб.</div>\n\
                <div class="col del" onclick="KouziList.del({id});">Х</div>\n\
                <div class="clearfix"></div>\n\
            </div>',
        
    article: null,
    
    viev:function(){
            jQuery('#article-list .item-block').html('');
            if (KouziList.article === null || KouziList.article.length === 0){                
                jQuery('#article-list .list-clear').show();
                jQuery('#article-list #total-price').html('0');                
            }else{
                var ind=1;                
                var total=0;
                jQuery('#article-list .list-clear').hide();
                KouziList.article.forEach(function(item) {
                                var tmp = KouziList.tpl_item; 
                                tmp = tmp.replace('{ind}',ind);
                                ind++;
                                for (var key in item){                                  
                                    tmp = tmp.replace(new RegExp('{'+key+'}','g'),item[key]);
                                    if(key==='total')total+=item[key];
                                }
                                jQuery('#article-list .item-block').append(tmp);
                                jQuery('#article-list #total-price').html(total+' руб.');
                });   
            }        
    },
    
    load:function(responseData){
            KouziList.article = responseData.article;  
            var art;
            for(var i=0;i<KouziList.article.length;i++){
                art = KouziCatalog.getItem(KouziList.article[i].id);   
                if(art){
                    KouziList.article[i].price = art.price;
                    KouziList.article[i].name = art.name;
                    KouziList.article[i].total = art.price * KouziList.article[i].count;
                }
            }
            KouziList.viev();
    },
    
    add:function(articul,count){     
        for(var i=0;i<KouziList.article.length;i++){        
            if(KouziList.article[i].id===articul){
                KouziList.article[i].count+=count;
                KouziList.article[i].total = KouziList.article[i].price * KouziList.article[i].count;
                KouziList.viev();
                KouziList.sendEdit();                
                return;                 
            }
        }
        var art = KouziCatalog.getItem(articul);
        if(art){
                KouziList.article.push({
                    'name' :art.name,
                    'price':art.price,
                    'total':art.price*count,
                    'count':count,
                    'id'   :articul
                });
                KouziList.viev();
                KouziList.sendEdit();            
        }
    },
    
    del:function(id){
        for(var i=0;i<KouziList.article.length;i++){
            if(KouziList.article[i].id===id){
                KouziList.article.splice(i,1);
                KouziList.viev();
                KouziList.sendEdit();                
                break; 
            }
        }
    },
    
    clear:function(){
        if(KouziList.article.length>0 && confirm('Очистить корзину?')){
            KouziList.article.length = 0; 
            KouziList.viev();
            KouziList.sendEdit();
        }
    },
    
    sendEdit:function(){
        KouziShop.sendList();
    }
};
