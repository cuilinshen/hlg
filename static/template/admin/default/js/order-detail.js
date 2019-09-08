/**
 * @version $Id$
 * @create 2012-9-26 10:29:57 By xjiujiu
 * @description HongJuZi Framework
 * @copyRight Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */

/**
 * 详细信息页的初始化信息 
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package js 
 * @since 1.0.0
 */
HHJsLib.register({
    init: function() {
        this.bindEditOrderAddress();
        this.bindEditOrderFapiao();
        this.bindEditOrderWuliu();
        this.bindEditOrderGoods();
        this.bindBtnAddOrderZengpin();
        this.bindBtnDel();
        this.bindBtnModalSubmitReload();
        this.bindBtnEditRedpackage();
        this.bindBtnEditActivity();
        this.bindBtnEditOrderWuliu();
    },
    updateOrderSum: function(callback) {
        $.getJSON(
            queryUrl + 'admin/order/asum',
            {id: oid},
            function(response) {
                if(false === response.rs) {
                    return HHJsLib.warn(response.message);
                }
                if(typeof(callback) !== 'undefined') {
                    callback(response);
                }
            }
        );
    },
    bindBtnEditActivity: function() {
        var self    = this;
        $('#btn-edit-activity').click(function() {
            $('#order-modal-title').html('修改优惠活动信息');
            var tpl = $('#edit-activity-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').modal('show');
            $.getJSON(
                queryUrl + 'admin/shopactivity/alist',
                {uid: uid, oid: oid},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    var itemTpl         = $('#edit-activity-item-template').html();
                    var itemHtml        =  '';
                    for(var ele in response.data) {
                        var item        = response.data[ele];
                        itemHtml        += itemTpl.replace(/{id}/g, item.id)
                        .replace(/{min_money}/g, item.min_money)
                        .replace(/{type}/g, item.type)
                        .replace(/{key}/g, (parseInt(ele) + 1))
                        .replace(/{sub_money}/g, item.sub_money);
                    }
                    if(!itemHtml) {
                        itemHtml    = '<tr><td colspan="5" align=center>暂无可用优惠！</td></tr>';
                    }
                    $('#edit-activity-box').html(itemHtml);
                    self.bindBtnUseForActivity('#edit-activity-box a.btn-use');
                }
            );
        });
    },
    bindBtnUseForActivity: function(dom) {
        var self    = this;
        $(dom).click(function() {
            if(!confirm('注意：使用此优惠活动后，原优惠活动将会失效，确认使用吗？')) {
                return;
            }
            var id  = $(this).attr('data-id');
            $.getJSON(
                queryUrl + 'admin/orderactivity/aedit',
                {oid: oid, aid: id},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    HHJsLib.succeed('优惠活动编辑成功！');
                    $("#new-activity-item-" + id).fadeOut('slow', function() {
                        $(this).remove();
                    });
                    self.updateOrderSum();
                }
            );
        });
    },
    bindBtnEditRedpackage: function() {
        var self    = this;
        $('#btn-edit-redpackage').click(function() {
            $('#order-modal-title').html('修改红包信息');
            var tpl = $('#edit-redpackage-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').modal('show');
            $.getJSON(
                queryUrl + 'admin/redpackage/alist',
                {uid: uid, oid: oid},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    var itemTpl         = $('#edit-redpackage-item-template').html();
                    var itemHtml        =  '';
                    for(var ele in response.data) {
                        var item        = response.data[ele];
                        itemHtml        += itemTpl.replace(/{id}/g, item.id)
                        .replace(/{desc}/g, item.description)
                        .replace(/{key}/g, (parseInt(ele) + 1))
                        .replace(/{endTime}/g, (new Date(1000 * parseInt(item.end_time))).toLocaleString())
                        .replace(/{money}/g, item.money);
                    }
                    if(!itemHtml) {
                        itemHtml    = '<tr><td colspan="5" align=center>暂无可用红包！</td></tr>';
                    }
                    $('#edit-redpackage-box').html(itemHtml);
                    self.bindBtnUseForRedpackage('#edit-redpackage-box a.btn-use');
                }
            );
        });
    },
    bindBtnUseForRedpackage: function(dom) {
        $(dom).click(function() {
            if(!confirm('注意：使用此红包后，原红包将会失效，确认使用吗？')) {
                return;
            }
            var id  = $(this).attr('data-id');
            $.getJSON(
                queryUrl + 'admin/order/aeditredpackage',
                {oid: oid, rid: id},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    HHJsLib.succeed('红包编辑成功！');
                    $("#new-redpackage-item-" + id).fadeOut('slow', function() {
                        $(this).remove();
                    });
                    self.updateOrderSum();
                }
            );
        });
    },
    bindBtnDel: function() {
        $('a.btn-del').click(function() {
            if(!confirm('您真的要删除它吗？注意，删除后将无法找回！')) {
                return false;
            }
            return true;
        });
    },
    bindEditOrderAddress: function() {
        var self = this;
        $("#edit-order-address").click(function() {
            $('#order-modal-title').html('修改收货人信息');
            var tpl = $('#edit-order-address-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').find('.btn-edit').attr('id', 'address-modal-btn');
            $('#order-modal').modal('show');
            self.bindSaveOrderAddress();
        });
    },
    bindSaveOrderAddress: function() {
        $('#order-modal').find('#address-modal-btn').unbind().on('click', function() {
            var id          = $('#id').val();
            var receiver    = $('#receiver').val();
            var phone       = $('#phone').val();
            var address     = $('#dialog-address').val();
            var zipcode     = $('#zipcode').val();
            $.getJSON(
                queryUrl + '/admin/orderaddress/aeditaddress',
                {id: id, receiver: receiver, phone: phone, address: address, zipcode: zipcode},
                function(response){
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    window.location.reload();
                }
            );

        });
    },
    bindEditOrderFapiao: function() {
        var self = this;
        $("#edit-order-fapiao").click(function() {
            $('#order-modal-title').html('修改发票信息');
            var tpl = $('#edit-order-fapiao-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').find('.btn-edit').attr('id', 'fapiao-modal-btn');
            $('#order-modal').modal('show');
            self.bindSaveOrderFapiao();
        });
    },
    bindSaveOrderFapiao: function() {
        $('#order-modal').find('#fapiao-modal-btn').unbind().on('click', function() {
            var id          = $('#fapiao-id').val();
            var orderId     = $('#id').val();
            var type        = $('#fapiao-type').val();
            var name        = $('#fapiao-name').val();
            var content     = $('#fapiao-content').val();
            $.getJSON(
                queryUrl + '/admin/orderfapiao/aeditfapiao',
                {order_id: orderId, id: id, type: type, name: name, content: content},
                function(response){
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    window.location.reload();
                }
            );

        });
    },
    bindEditOrderWuliu: function() {
        var self = this;
        $("#edit-order-wuliu").click(function() {
            $('#order-modal-title').html('修改物流信息');
            var tpl = $('#edit-order-wuliu-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').find('.btn-edit').attr('id', 'wuliu-modal-btn');
            $('#order-modal').modal('show');
            self.bindSaveOrderWuliu();
        });
    },
    bindSaveOrderWuliu: function() {
        $('#order-modal').find('#wuliu-modal-btn').unbind().on('click', function() {
            var id          = $('#id').val();
            var name        = $('#wuliu-name').val();
            var code        = $('#wuliu-code').val();
            var date        = $('#wuliu-date').val();
            var content     = $('#wuliu-content').val();
            $.getJSON(
                queryUrl + '/admin/orderwuliu/aeditwuliu',
                {id: id, name: name, code: code, date: date, content: content},
                function(response){
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    window.location.reload();
                }
            );

        });
    },
    bindEditOrderGoods: function() {
        var self = this;
        $("#edit-order-goods").click(function() {
            $('#order-modal-title').html('修改商品信息');
            var tpl = $('#edit-order-goods-template').html();
            $('#order-modal-content').html(tpl);
            $('#order-modal').find('.btn-edit').attr('id', 'goods-modal-btn');
            $('#order-modal').modal('show');
            self.bindGoodPriceChange();
            self.bindGoodNumberChange();
            self.bindSaveOrderGoods();
        });
    },
    bindGoodPriceChange: function() {
        $('#order-modal-content').find('.goods-price').unbind().on('change', function(){
            var price   = parseFloat($(this).val());
            var number  = parseInt($(this).parent('td').siblings('td').find('.goods-number').val());
            var totalPrice = price * number;
            $(this).parent('td').siblings('td').find('.item-price').html(totalPrice);
        });
    },
    bindGoodNumberChange: function() {
        $('#order-modal-content').find('.goods-number').on('change', function(){
            var number   = parseInt($(this).val());
            var price  = parseFloat($(this).parent('td').siblings('td').find('.goods-price').val());
            var totalPrice = parseFloat(price * number).toFixed(2);
            $(this).parent('td').siblings('td').find('.item-price').html(totalPrice);
        });
    },
    bindSaveOrderGoods: function() {
        var self = this;
        $('#order-modal').find('#goods-modal-btn').unbind().on('click', function() {
            var data        = new Array();
            $('#order-modal-content').find('.goods-item').each(function(){
                var $this   = $(this);
                var id      = $this.find('.goods-id').val();
                var price   = parseFloat($this.find('.goods-price').val());
                var number  = parseInt($this.find('.goods-number').val());
                data.push({id: id, price: price, number: number});
            });
            $.getJSON(
                queryUrl + '/admin/ordergoods/aeditgoods',
                {data: data},
                function(response){
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    self.updateOrderSum(function() {
                        window.location.reload();
                    });
                }
            );

        });
    },
    bindBtnAddOrderZengpin: function() {
        var self    = this;
        $("#btn-add-order-zengpin").click(function() {
            $('#order-modal-title').html('修改赠品信息');
            var tpl = $('#edit-order-zengpin-template').html();
            $('#order-modal-content').html(tpl);
            $.getJSON(
                queryUrl + 'admin/zenping/alist',
                {oid: oid},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    var itemHtml    = '';
                    var tpl         = $('#zenping-item-template').html();
                    for(var ele in response.data) {
                        var item    = response.data[ele];
                        var img     = '';
                        if(item.image_path) {
                            src     = 0 > item.image_path.indexOf('http') ? siteUrl + item.image_path : item.image_path;
                            img     = '<a href="' + src + '" class="lightbox"><img width="80" src="' + src + '"/></a>';
                        }
                        itemHtml    = tpl.replace(/{id}/g, item.id)
                        .replace(/{key}/g, (parseInt(ele) + 1))
                        .replace(/{name}/g, item.name)
                        .replace(/{content}/g, item.description)
                        .replace(/{price}/g, item.price)
                        .replace(/{number}/g, item.number)
                        .replace(/{last_number}/g, item.last_number)
                        .replace(/{img}/g, img);
                    }
                    $('#zenping-list-box').html(itemHtml);
                    self.bindBtnAddZenPingToOrder('#zenping-list-box a.btn-add');
                    HHJsLib.bindLightBox('#zenping-list-box a.lightbox', cdnUrl + 'jquery/plugins/lightbox');
                }
            );
            $('#order-modal').modal('show')
        });
    },
    bindBtnModalSubmitReload: function() {
        $('#btn-modal-submit').click(function() {
            $('#order-modal').modal('hide');
            window.location.reload();
        });
    },
    bindBtnAddZenPingToOrder: function(dom) {
        $(dom).click(function() {
            var id  = $(this).attr('data-id');
            $.getJSON(
                queryUrl + 'admin/orderzenping/aadd',
                {oid: oid, sid: id, uid: uid},
                function(response) {
                    if(false === response.rs) {
                        return HHJsLib.warn(response.message);
                    }
                    $('#add-zenping-item-' + id).fadeOut(function() {
                        $(this).remove();
                    });
                    HHJsLib.succeed('添加成功！');
                }
            );
        });
    },
    bindBtnEditOrderWuliu: function() {
        $('.btn-edit-order-wuliu').click(function(){
            $('#edit-order-wuliu').trigger('click');
        });
    }
});
