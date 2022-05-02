<hr>
<div class="dish_card" id="dish_[[+id]]" data-id="[[+id]]" data-baseprice="[[+price:htmlent]]" data-delta="[[+newprice::htmlent]]" data-price="[[+price]]" data-name="[[+pagetitle:htmlent]]">
    <div class="row" id="inline">
        <div class="col-7" data-toggle="modal" data-target="#modal_[[+id]]">
            <p class="line-2 font-weight-bold mb-1">[[+pagetitle]]</p>
            <div class="dish-description small">
                [[+content:ellipsis=`100`]]
                <!--div><span class="small">[[+volume:after=` / `]] [[+kbju]]</span></div-->
            </div>
            <div class="mt-2 small line-2">
                [[+option1:notempty=`Выбор:
                <a href=# class="selected_options" onclick="return(false);"></a>`
                ]]
            </div>
        </div>
        <div class="col-5 text-right">
            <div data-toggle="modal" data-target="#modal_[[+id]]">
                [[+image1:notempty=`<div class="mb-3">
                    <img class="img-fluid img-thumbnail" src="/[[+image1]]" alt="[[+pagetitle:htmlent]]">
                </div>`
                ]]
            </div>
            <div>
                <div style="float:left">
                    <span class="dish_price [[+newprice:lt=`0`:then=`del`]]">[[+price]]</span> руб.
                </div>
                <button type="button" class="btn btn-outline-primary px-3 px-md-5 mt-n1" style="float:right" onclick="add2cart('[[+id]]'); return(false);">
                    [[svg?&name=`cart-plus.svg`]]
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="modal_[[+id]]" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">[[+pagetitle]]</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    [[+image1:notempty=`<img src="/[[+image1]]" class="img-fluid">`]]
                    <div class="my-3 pr-4 dish_options">[[option2select?txt=[[+option1]]&id=`[[+id]]`]]</div>
                    <div>[[+content]]</div>
                    [[+volume:notempty=`<div>Размер порции: [[+volume]]</div>`]]
                    [[+kbju:notempty=`<div>Пищевая ценность: [[+kbju]]</div>`]]
                </div>
                <div class="modal-footer">
                    <div class="col text-left">
                        <button type="button" class="btn btn-outline-primary mt-n1" data-dismiss="modal">Закрыть</button>
                    </div>
                    <div class="col text-right" >
                        <span class="dish_price">[[+price]]</span> руб.
                        <button type="button" class="btn btn-outline-primary px-3 px-md-5 mt-n1 ml-3" onclick="add2cart('[[+id]]'); return(false);">
                            [[svg?&name=`cart-plus.svg`]]
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

