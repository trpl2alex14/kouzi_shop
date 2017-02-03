KouziShop = {
    url : '', 
    successUrl:'/',
    clientid: 0,
    
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
    
    ajaxReq:function(req, data,callbk){        
        jQuery.ajax({type: "POST",
                url: KouziShop.url+"route.php",  
                dataType: "json",                
                data: 'sendreq='+req+'&jsonData=' + jQuery.toJSON(data),
                success: function(data){                                                                                                                                
                        switch(data.status){
                            case 'success':	
                                if (typeof callbk === 'function') {
                                    callbk(data);
                                }
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
        if(responseData.clientid){
            KouziShop.clientid = responseData.clientid;
        }else{
            //error no id clinet
        }
        KouziCatalog.load(responseData);
        KouziList.load(responseData);
        RalPicker.load(responseData);
        KouziOrder.load(responseData);
        CityPicker.init("#city",responseData);        
    },
    
    init:function(wrapper){
        this.shopWrapper.idblock = wrapper;
        this.shopWrapper.callbk = this.ready;
        this.load(this.shopWrapper,"index");
    },
    
    sendList: function(){   
        var data = {
            clientid: this.clientid,
            article:KouziList.article            
        };        
        this.ajaxReq('article',data);
    },
    
    sendOrder:function(){        
        if(!KouziOrder.checkOrder()){
            return false;
        }        
        KouziOrder.getInputData(); 
        var data = {
            clientid: this.clientid,
            article:KouziList.article,
            order  :KouziOrder.order
        };
        this.ajaxReq('order',data);        
        return true;
    },
    
    pay:function(){        
        window.location = "route.php?sendreq=pay&clientid="+this.clientid;
    },
    
    applay:function(){
        jQuery("#action-3").hide();        
        this.ajaxReq("createdeal&clientid="+this.clientid,null,function(responseData){
            jQuery('#load_modal').show();
            jQuery('#load_modal .back').click(function(){
                window.location = KouziShop.successUrl;
            });
        });        
    },
    
    nextStep: function(step){
        if(step === 0){
            jQuery(this.shopWrapper.idblock+" .order").hide();
            jQuery("#action-2").hide();
            
            jQuery("#action-3").hide();
            jQuery("#delivery-info").hide();
            jQuery("#all-info").hide();
            jQuery("#order-info").hide();
                        
            jQuery(this.shopWrapper.idblock+" .catalog").show();
            jQuery("#action-1").show();            
            jQuery("#article-list .item-block .del").show();

            jQuery('html, body').animate({ scrollTop: jQuery(this.shopWrapper.idblock+" .catalog").offset().top }, 500);
        }else
        if(step === 1){
            if(KouziList.article.length > 0){
                this.sendList();                      
                jQuery(this.shopWrapper.idblock+" .catalog").hide();
                jQuery("#action-1").hide();            
                jQuery("#article-list .item-block .del").hide();
                jQuery("#order-info").hide();

                jQuery(this.shopWrapper.idblock+" .order").show();
                jQuery("#action-2").show();
                jQuery('html, body').animate({ scrollTop: jQuery("#article-list").offset().top }, 500);
            }
        }else 
        if(step === 2){
            if(this.sendOrder()){                  
                if(KouziOrder.order.type === 0){
                    jQuery("#order-info .client-info").html(KouziOrder.order.lname+' '+KouziOrder.order.fname+' '+KouziOrder.order.pname );
                    jQuery("#order-info .contact-info span").html(KouziOrder.order.phone);
                }else{
                    jQuery("#order-info .client-info").html(KouziOrder.order.companyname+' (ИНН: '+KouziOrder.order.inn+')');
                    jQuery("#order-info .contact-info span").html(KouziOrder.order.cphone);
                }                           
                
                var true_city = CityPicker.getCityId(KouziOrder.order.city);
                var delivery = 0;
                var article = KouziList.getTotalPrice();
                
                if(true_city >= 0){
                    if(KouziOrder.order.logistic === 0){
                        jQuery("#order-info .address-info span").html('склада г. '+KouziOrder.order.city);                 
                    }else{
                        jQuery("#order-info .address-info span").html('адреса г. '+KouziOrder.order.city+' '+KouziOrder.order.address);                 
                    }                    
                    jQuery("#order-info .time-info").html(CityPicker.getTime(KouziOrder.order.logistic,KouziOrder.order.city)+' дн.'); 
                    delivery = CityPicker.getPrice(KouziOrder.order.logistic,KouziOrder.order.city);
                    jQuery("#order-info .price-block").show();
                    if(KouziOrder.order.payment === 0){
                        jQuery("#order-info .price-block span").html(Number(delivery)+Number(article));
                        jQuery("#order-info .post-pay").hide();
                    }else{
                        jQuery("#order-info .price-block span").html(delivery);
                        jQuery("#order-info .post-pay").show();                    
                    }                    
                    jQuery("#delivery-total").html(delivery + " руб.");
                    jQuery("#total-all").html((Number(delivery)+Number(article)) + " руб.");

                    jQuery("#delivery-info").show();
                    jQuery("#all-info").show();

                    if(KouziOrder.order.type === 0){    
                        jQuery("#pay-btn").show(); 
                        jQuery("#applay-btn").hide(); 
                    }else{
                        jQuery("#pay-btn").hide(); 
                        jQuery("#applay-btn").show();                        
                    }     
                }else {
                    if(KouziOrder.order.logistic === 0){
                        jQuery("#order-info .address-info span").html('склада ');                 
                    }else{
                        jQuery("#order-info .address-info span").html('адреса '+KouziOrder.order.address);                 
                    }                    
                    jQuery("#order-info .time-info").html('необходимо уточнить у оператора');
                    jQuery("#order-info .price-block").hide();
                    
                    jQuery("#delivery-info").hide();
                    jQuery("#all-info").hide();                    
                    jQuery("#pay-btn").hide(); 
                    jQuery("#applay-btn").show(); 
                }
                                   
                jQuery(this.shopWrapper.idblock+" .order").hide();
                jQuery("#action-2").hide();

                jQuery("#order-info").show();
                jQuery("#action-3").show();

                jQuery('html, body').animate({ scrollTop: jQuery("#article-list").offset().top }, 500);
            }else{
                jQuery('html, body').animate({ scrollTop: jQuery(this.shopWrapper.idblock+" .order").offset().top }, 500);    
            }
        }       
    }
};

KouziOrder = {
    order : {
        type : 0,
        payment:0,
        logistic:0,        
        fname : '',
        lname: '',
        pname: '',
        phone: '',
        email:'',
        cname:'',
        inn:'',
        city:'Челябинск',        
        address:'',        
        comment:'',
        companyname:'',
        cphone: '',
        cemail:''        
    },
    
    load: function(responseData){
        if(responseData.order){
            this.order = responseData.order;            
        }
        for(var key in this.order){
            if(key !== 'type' || key !== 'payment' || key !== 'logistic'){
                jQuery("#"+key).val(this.order[key]);
            }
        }
        if(this.order.type === 0){
            this.setClient("person",jQuery(".client-select li:eq(0)"));
        }else{                        
            this.setClient("person",jQuery(".client-select li:eq(1)"));
        }        
        if(this.order.logistic === 0){            
            jQuery("#logistic-1").prop('checked',true);     
            jQuery(".address-block").hide();
        }else{            
            jQuery("#logistic-2").prop('checked',true);
            jQuery(".address-block").show();
        }
        if(this.order.payment === 0){   
            jQuery("#payment-1").prop('checked',true);     
        }else{
            jQuery("#payment-2").prop('checked',true);                 
        }      
        jQuery('.logistic-block input[name=logistic]').click(function(){
            KouziOrder.order.logistic=Number(jQuery('.logistic-block input[name=logistic]:checked').val());
            if(KouziOrder.order.logistic === 0){
                jQuery(".address-block").hide();
            }else{
                jQuery(".address-block").show();
            }
        });
        jQuery('.logistic-block input[name=payment]').click(function(){
            KouziOrder.order.payment=Number(jQuery('.logistic-block input[name=payment]:checked').val());
        });                    
    },
    
    getInputData: function(){       
       this.order.lname     = jQuery('.client-block input[name=lname]').val();
       this.order.fname     = jQuery('.client-block input[name=fname]').val();
       this.order.pname     = jQuery('.client-block input[name=pname]').val();
       this.order.phone     = jQuery('.client-block input[name=phone]').val();
       this.order.email     = jQuery('.client-block input[name=email]').val();
       
       this.order.cname      = jQuery('.client-block input[name=cname]').val();
       this.order.companyname= jQuery('.client-block input[name=companyname]').val();
       this.order.inn        = jQuery('.client-block input[name=inn]').val();
       this.order.cphone     = jQuery('.client-block input[name=cphone]').val();
       this.order.cemail     = jQuery('.client-block input[name=cemail]').val();
        
       this.order.logistic = Number(jQuery('.logistic-block input[name=logistic]:checked').val());
       this.order.address  = jQuery('.logistic-block input[name=address]').val();
       this.order.city     = CityPicker.getCity();
       
       this.order.payment  = Number(jQuery('.payment-block input[name=payment]:checked').val());
       
       this.order.comment  = jQuery('#comment').val();
    },
    
    setClient: function(client,el){
        if(client === 'person'){
            jQuery("#person").show();
            jQuery("#company").hide();
            this.order.type = 0;   
            jQuery("#payment-2").show();
            jQuery("label[for=payment-2]").show();
        }else{
            jQuery("#company").show();
            jQuery("#person").hide();                        
            this.order.type = 1;
            this.order.payment = 0;
            jQuery("#payment-1").prop('checked',true);
            jQuery("#payment-2").hide();
            jQuery("label[for=payment-2]").hide();            
        }
        jQuery(".client-select li").removeClass("active");
        jQuery(el).addClass("active");        
    },
    
    typeCheck: function(name,value){
        var error = true;
                    switch(name){
                        case 'phone':
                        case 'cphone':
                            var regCheck = new RegExp('[^0-9\s-]+');
                            if(regCheck.test(value)){
                                error = false;
                            }                            
                        break;
                        case 'email':
                        case 'cemail': 
                            var regCheck = new RegExp("^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$");
                            if(!regCheck.test(value)){
                                error = false;
                            }                            
                        break;                    
                    }        
        return error;
    },
    
    elementChecking:function(element){
        var name = jQuery(element).prop("name");
        var box = jQuery(element).attr("fm_box");
        var error = true;
        if(typeof box === "undefined" || box == KouziOrder.order.type){                                            
            if(jQuery(element).attr("fm_check") === "y"){                        
                if(jQuery(element).val().length === 0){
                    error = false;
                }else {
                    error = KouziOrder.typeCheck(name,jQuery(element).val());
                }
            }else{
                if(jQuery(element).val().length > 0){
                    error = KouziOrder.typeCheck(name,jQuery(element).val());
                }
            }
        }
        if(!error){
            jQuery(element).addClass("error-check");
            jQuery(".order ."+name+"-error").show();            
        }
        return error;
    },
    
    checkOrder:function(){        
        var error=true;        
        jQuery(".order input").removeClass("error-check");
        jQuery(".order .error-text").hide();
        var elements = jQuery(".order").find('[fm_check]');
        elements.each(function(){
            if(error){
                error=KouziOrder.elementChecking(this);
            }
            else {
                KouziOrder.elementChecking(this);
            }
        });         
        return error;
    }    
};

CityPicker = {
    city : {
        price:[
            1000,
            0,
            500
        ],
        curier: [
            400,
            0,
            200
        ],
        time:[
          '4-6',
          '1-2',
          '2-3'
        ],
        name: [
            "Москва",
            "Челябинск",
            "Пермь"
        ]
    },
    name:'',
    init : function(name,responseData){
        this.name = name;
        if(responseData.city){
            this.city = responseData.city;   
        }
        this.city.name.forEach(function(item) {
            jQuery(CityPicker.name).append('<option value="'+item+'" '+(KouziOrder.order.city === item ? 'selected' : '')+'>'+item+'</option>');
        });
        jQuery(this.name).chosen({
            width: "350px", 
            create_option: true,      
            persistent_create_option: true,    
            skip_no_results: true
        });
        this.setCityOrder();
        $(this.name).on('change', function(evt, params){ 
               CityPicker.setCityOrder();
        });        
    },
    
    setCityOrder:function(){
            var city = CityPicker.getCity();
            var id = CityPicker.getCityId(city);
            if(id >= 0){
                jQuery("label[for=logistic-1] span").html('г. '+city);                 
                jQuery("label[for=logistic-2] span").html('( + '+CityPicker.city.curier[id]+' руб. )');             
            }else{
                jQuery("label[for=logistic-1] span").html('');
                jQuery("label[for=logistic-2] span").html('');             
            }        
    },
    
    getCityId: function(name){
        for(var i=0; i< this.city.name.length ; i++){
            if(this.city.name[i] === name)
                return i;
        }        
        return -1;
    },
    
    getCity:function(){
        return jQuery(CityPicker.name).val();
    },
    getPrice:function(type,city){
        var id = this.getCityId(city);
        if(id >= 0){
            if(type === 0){
                return this.city.price[id];
            }else{
                return Number(this.city.price[id])+Number(this.city.curier[id]);
            }
        }
        return 0; 
    },

    getTime:function(type,city){
        var id = this.getCityId(city);
        if(id >= 0){      
            return this.city.time[id];
        }
        return 'уточняется'; 
    }    
};
   
RalPicker = {   
    color : null,
    callbk: null,
    
    load : function(responseData){
        RalPicker.color = responseData.ral;
    },
    
    show: function(callbk){
        this.callbk = callbk; 
        var tmp='<h3>Выберите цвет</h3>';
        var j=0;        
        for(var i=0;i<this.color.length;i++){
            tmp+='<div onclick="RalPicker.select(this);" class="box" style="background:'+this.color[i].hex+';">'+this.color[i].name+'</div>';
             j++;
            if(j===12){
                tmp+='<div class="clearfix"></div>';               
                j=0;
            }
        }
        tmp+='<div class="clearfix"></div>';
        jQuery('#ral_modal .modal_container').html(tmp);
        jQuery('#ral_modal').show();
    },
    
    close:function(){
        jQuery('#ral_modal').hide();
    },
    
    select: function(element){
        var col = jQuery(element).html();
        RalPicker.callbk(col);
        RalPicker.close();
    }
};

KouziCatalog = {
        tpl_item : '<div class="item">\n\
                    <h4>{name}</h4>\n\
                    <img src="image/{img}">\n\
                    <p>{info}</p>\n\
                    <div class="price">{price} руб.</div> \n\
                    <div class="action-block">\n\
                    <a class="btn about" onclick="KouziCatalog.infoArticle({id});">Подробнее</a>\n\
                    <a class="btn btn-add" onclick="KouziCatalog.addArticle({id});">В корзину</a>\n\
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
                                        data:'<div class="info-block"><p><img src="image/'+art.img+'"></p><h3>'+art.name+'</h3><p>'+art.info+'</p></div><div class="components-block">'
                                    }                                   
                                 };
                if(art.type==='d' && art.model){
                    for(var i=0;i<art.model.length;i++){                        
                        if(art.model[i].type){
                            var key = (art.model[i].type==='color')? 'color':'line'+Number(i+2);
                            components[key] = {
                                type : 'checkbox',
                                data : '<h3>'+art.model[i].name+'</h3>',
                                value: art.model[i].item[0].atrmod,
                                item : art.model[i].item,
                                color: art.model[i].type
                            };
                        }
                    }
                }
                
                components['count'] = {
                                        type:'count',
                                        data:'Укажите колличество',
                                        value:1
                }; 
                components['footer'] = {
                                        type:'text',
                                        data:'</div>'
                };                
                
                KouziModal.create(components,function(params){
                            KouziList.add(params.config.articul,params.count.value,params.config.color);                    
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
                if(KouziCatalog.catalog[i].articul==articul){
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
        var tmp = '';
        if(components){            
            this.components = components;
            var item;
            for(var key in this.components){
                item = this.components[key];
                switch (item.type){
                    case 'text':
                        tmp += item.data;
                    break;           
                    case 'count':
                       tmp += '<div class="component"><input type="number" size="2" name="count" min="1" max="99" value="1">'+item.data+'</div>';
                    break;
                    case 'checkbox':
                        tmp += '<div class="component">'+item.data;                        
                        for(var j=0;j<item.item.length;j++){                            
                            tmp += '<input type="radio" id="'+key+'-'+j+'" name="'+key+'" value="'+item.item[j].artmod+'" '+( j===0 ? 'checked':'')+'><label for="'+key+'-'+j+'">'+item.item[j].text+'</label><br>';
                        }      
                        tmp += '</div>';                        
                    break;                         
                }                
            }
        }
        $('#content_modal').append(tmp);
        $('#content_modal input[id=color-1]').click(function(){RalPicker.show(function(color){KouziModal.components.config.color=color;});});
        $('#content_modal input').change(function(){KouziModal.viewPrice.call(KouziModal);});        
        this.viewPrice();
        this.callbk = callbk;
        $('.fixed-overlay .price-box').show();
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
                $('#content_modal').html('<div class="info-modal">'+content+'</div>');
                $('.fixed-overlay').show();
                $('.fixed-overlay .action-block .btn').hide();
                $('.fixed-overlay .action-block .about').show(); 
                $('.fixed-overlay .price-box').hide();
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
                <div class="col name">{name}{comment}</div>\n\
                <div class="col price">{price} руб.</div>\n\
                <div class="col count">{count}</div>\n\
                <div class="col total">{total} руб.</div>\n\
                <div class="col del" onclick="KouziList.del({id});">Х</div>\n\
                <div class="clearfix"></div>\n\
            </div>',
        
    article: null,
    
    getTotalPrice: function(){
        var total = 0;
        for(var i = 0; i< KouziList.article.length;i++){
            total += KouziList.article[i].total;
        }
        return total;
    },
    
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
                                }
                                total+=item['total'];
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
    
    add:function(articul,count,comment){ 
        if(typeof comment === "undefined"){
            comment='';
        }        
        for(var i=0;i<KouziList.article.length;i++){        
            if(KouziList.article[i].id==articul && KouziList.article[i].comment===comment){ 
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
                    'id'   :articul,
                    'comment':comment
                });
                KouziList.viev();
                KouziList.sendEdit();            
        }
    },
    
    del:function(id){
        for(var i=0;i<KouziList.article.length;i++){
            if(KouziList.article[i].id==id){
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
