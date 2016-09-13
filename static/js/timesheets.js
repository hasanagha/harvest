;(function() {
    'use strict';
    // var ID_SELECTORS
    var timesheetsJS = {
        config: {
            dt              : "#timesheets_dt",
            form            : "#frm_timesheets",
        },
        urls: {
            single  : $('body').data('base-url') + "account/timesheets/get/",
            delete  : $('body').data('base-url') + "account/timesheets/delete/",
            timer   : $('body').data('base-url') + "account/timesheets/timer/",
        },
        titles: {
            new_record: "Add a New Entry",
            update_record: "Edit Entry: {}",
        },
        elem :{
            add_button      : "#btn_add",
            edit            : ".edit-record",
            delete          : ".delete-record",
            timer           : ".timer-record",
            delete_specific : ".delete-specific",
            change_status   : ".change-status",
            submit_button   : "#btn_save",
            cancel_button   : "#btn_cancel",
            form            : "#form-container",
            mainError       : ".main_error",
            resetButton     : "[type='reset']",
        },
        preloader : $(".preloader"),

        // TEMP
        init: function() {
            var _this = this;

            if($(_this.config.dt).length){
                //Generate DT
                _this.table = $(_this.config.dt).dataTable();

                _this._initTriggers();
            }

        },
        // Initialize all triggers
        _initTriggers: function() {
            var _this = this;
            //Init row click
            $(_this.elem.edit).click(function(){
                _this._getSingleData($(this).data('id'));
            });

            // Datepicker init
            $('#spent_at').dcalendarpicker({
                format: 'yyyy-mm-dd'
            });

            //init Submit button click
            $(_this.elem.submit_button).click(function(){
                _this._submitForm();
            });

            // + Add New button trigger.
            $(_this.elem.add_button).click(function(){
                document.frm_timesheets.reset(); // Reset the form first.
                $(_this.elem.form).find('.title').html(_this.titles.new_record); // change form title
                $(_this.elem.form).show(); // display form
                $(_this.elem.delete_specific).hide(); // hide delete entry option
            });

            // Delete action
            $(_this.elem.delete).click(function(){
                var r_id = $(this).data('id');
                _this._deleteEntry(r_id);
            });

            // Start/Stop Timer action
            $(_this.elem.timer).click(function(){
                var r_id = $(this).data('id');
                _this._toggleTimer(r_id);
            });

            //init Change Status
            $(_this.elem.change_status).click(function(){
                _this._changeStatus($(this));
            });

            // Cancel button trigger to close the form
            $(_this.elem.cancel_button).click(function(){
                $(_this.elem.form).hide(); // hide form
            });
        },
        // Method to request an entry by passing its ID.
        _getSingleData: function(id){
            var _this = this;
            if(id>0){
                var requested_url   = _this.urls.single+id;
                _this.preloader.show();
                $.get(requested_url, function(data){
                    if(data){
                        $(_this.elem.backToTop + " a").trigger("click");
                        _this.preloader.hide();
                        if(data && typeof(data) == "string"){
                            data = $.parseJSON(data);

                            if(!data.success) {
                                alert("No record found");
                                return;
                            }

                            _this._fillForm(data);
                        } else {
                            alert("No record found");
                        }
                    }
                });
            }
        },

        // Method to request an entry by passing its ID.
        _deleteEntry: function(entry_id){
            var _this = this;
            swal({
                title: "Are you sure to delete this entry?",
                text: "You will not be able to recover this!",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No!",
                closeOnConfirm: true,   
                closeOnCancel: true,
                showLoaderOnConfirm: true,
            }, function(){
                _this.preloader.show();
                $.get(_this.urls.delete + entry_id, function(data){
                    _this.preloader.hide();
                    if(typeof(data) == "string"){
                        data = $.parseJSON(data);
                    }
                    if(!data.success){
                        swal("Cannot Delete!", 'Please check if project has timesheets also.', 'error');
                    } else {
                        swal({
                            title: 'Success',
                            message: 'Entry deleted successfully',
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: 'OK',
                            closeOnConfirm: true
                        }, function() {
                            window.location.href = window.location.href;
                        });
                    }
                });
            });
        },

        // Method to request a status change by passing its ID.
        _toggleTimer: function(timesheet_id){
            var _this = this;
            if(timesheet_id){
                
                var requested_url   = _this.urls.timer+timesheet_id;
                _this.preloader.show();
                $.get(requested_url, function(data){
                    if(data){
                        _this.preloader.hide();
                        if(data && typeof(data) == "string"){
                            data = $.parseJSON(data);

                            if(!data.success) {
                                alert("No record found");
                                return;
                            }
                            
                            swal({
                                title: 'Success',
                                message: '',
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: 'OK',
                                closeOnConfirm: true
                            }, function() {
                                window.location.href = window.location.href;
                            });

                        } else {
                            alert("No record found");
                        }
                    }
                });
            }
        },

        // If entry is found using _getSingleData, parse the response and fill it in the form
        _fillForm: function(data){
            var _this = this;
            var title = _this.titles.update_record;
            var task_name = data.response.task + ', ' + data.response.project;
            title = title.replace('{}', task_name);
            $(_this.elem.form).find('.title').html(title);

            $(_this.elem.form).show();
            $(_this.elem.delete_specific).show();

            $("#timesheet_id").val(data.response.id);
            $("#project_id").val(data.response.project_id);
            $("#task_id").val(data.response.task_id);
            $("#duration").val(data.response.hours);
            $("#spent_at").val(data.response.spent_at);
            $("#notes").val(data.response.notes);

        },

        _submitForm: function(){
            var _this = this;
            var form = $(_this.config.form);
            _this.preloader.show();
            var options = {
                target      : form,
                resetForm   : false,
                success     : function(data) {
                    if(!data.success){
                        swal('Error', data.message, 'error');
                    }else{
                        swal({ title: "Success!", text: "Entry added/updated successfully" }, function(){
                            location.reload();
                        });
                    }
                    _this.preloader.hide();
                },
                dataType    : 'json'
            };
            form.ajaxSubmit(options);
        },
    }
    $(document).ready(function() {
        timesheetsJS.init();
    });
})();
