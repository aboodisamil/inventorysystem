@extends('dashboard.layout.app')
@section('content')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    الطلبيات </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a class="kt-subheader__breadcrumbs-link">
                        طلبيات قيد التنفيذ </a>
                </div>
            </div>

        </div>
    </div>



    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
										<span class="kt-portlet__head-icon">
											<i class="kt-font-brand flaticon2-list"></i>
										</span>
                <h3 class="kt-portlet__head-title">
                    طلبيات قيد التنفيذ
                </h3>
            </div>

            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">

                    <div class="dropdown dropdown-inline">

                        <a type="button" class="btn btn-bold btn-label-brand btn-sm"
                           href="{{route('dashboard.export.create')}}">اضافة طلبية جديدة
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body kt-portlet__body--fit">

            <div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="local_data"
                 style="">
                <table class="kt_datatable">

                </table>

            </div>
        </div>

        {{--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
        {{--aria-hidden="true">--}}
        {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content">--}}
        {{--<div class="modal-header">--}}
        {{--<h5 class="modal-title" id="exampleModalLabel">المنتجات المطلوبة</h5>--}}
        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
        {{--<span aria-hidden="true">&times;</span>--}}
        {{--</button>--}}
        {{--</div>--}}

        {{--<div class="modal-body">--}}

        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}


        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeLg"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تنفيذ الطلبية</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            var datatable = $(".kt_datatable").KTDatatable(
                {
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                method: 'GET',
                                url: '{{ route('dashboard.order.suspend') }}',
                            },
                        },
                        pageSize: 10,
                        serverPaging: !0,
                        serverFiltering: !0,
                        serverSorting: !0,
                    },
                    layout: {scroll: !1, footer: !1},
                    sortable: !0,
                    pagination: !0,
                    search: {input: $("#generalSearch"), key: "generalSearch"},
                    columns: [{
                        field: "id",
                        title: "#",
                        sortable: "asc",
                        width: 30,
                        type: "number",
                        selector: !1,
                        textAlign: "center"
                    }, {
                        field: "customer_id", title: "اسم الزبون", template: function (row) {
                            return row.customer.name
                        }
                    },

                        {
                            field: "user_id", title: "تم ادخال عن طريق", template: function (row) {
                                return row.user.name
                            }
                        },

                        {
                            field: "dateTime", title: "وقت و تاريخ الطلبية"
                        },
                        {
                            field: "notes", title: "ملاحظات"
                        },
                        {
                            field: "status", title: "الحالة", template: function (row) {
                                if (row.status === 'قيد التنفيذ')
                                    return '<span class="badge badge-primary">' + row.status + '</span>';
                                else if (row.status === 'غير مكتمل')
                                    return '<span class="badge badge-danger">' + row.status + '</span>';
                                else
                                    return '<span class="badge badge-success">' + row.status + '</span>';
                            }
                        },
                        {
                            field: "action", title: "العمليات", template: function (row) {
                                return '<a data-id="' + row.id + '" href="{{url('dashboard/export')}}/' + row.id + '" class="btn-lg execute"><i class="fa fa-play"></i></a>'

                            }
                        },

                    ],
                });
            $('#kt_form_status').on('change', function () {
                datatable.search($(this).val().toLowerCase(), 'status');
            });

            $(document).on('click', '.execute', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).attr('data-id');

                $.ajax({

                    type: "get",
                    url: url,
                    success: function (data) {
                        $('.modal-body').html(data);
                        $('#exampleModal').modal().show()

                    }

                })
            });

            $(document).on('click' , '.save', function (e) {

                e.preventDefault();

              //  var checkbox=$('input[type="checkbox"]').val();

                var $val=''
                if ($('input[type="checkbox"]').prop('checked') === true)
                {
                    $val='1'
                }
                else
                    {
                        $val='2'
                    }
                var form = $('#suspendForm') , url = form.attr('action');
                $.ajax({
                    type:'post' ,
                    url:url ,
                    data:form.serialize(),
                    success:function (data)
                    {
                        datatable.reload();
                        $('#exampleModal').modal('hide')
                    }
                })
            })
        });
    </script>

@endsection


