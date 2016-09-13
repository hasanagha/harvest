<h1>Manage Projects</h1>

<div id="form-container" class="d6-form full-width-form" >
    <div class="close-modal"></div>
    <form name="frm_projects" id="frm_projects" method="post" class="login-form-wrapper" action="<?=site_url('account/projects/addedit')?>">
        <input type="hidden" name="project_id" id="project_id" />
        <fieldset>
            <legend class="title">Add a New Project</legend>
            <div class="field">
                <label>Client</label>
                <select name="client_id" id="client_id">
                    <? foreach($clients as $client) {?>
                        <option value="<?=$client->client->id?>"><?=$client->client->name?></option>
                    <? }?>
                </select>
            </div>
            <div class="field">
                <label>Name</label>
                <input name="project_name" id="project_name" value="" placeholder="Project name *" type="text" />
            </div>
            <div class="field">
                <label>Code</label>
                <input name="project_code" id="project_code" value="" placeholder="Project Code *" type="text" />
            </div>
            <div class="field">
                <label>Start On</label>
                <input name="starts_on" id="starts_on" value="" placeholder="Project starting date" type="text" />
            </div>
            <div class="field">
                <label>Ends On</label>
                <input name="ends_on" id="ends_on" value="" placeholder="Project ending date" type="text" />
            </div>
            <div class="field">
                <label>Active</label>
                <input type="checkbox" name="active" id="active" />
            </div>
            <div class="clear"></div>
            <div class="field">
                <label>Notes</label>
                <textarea name="notes" id="notes" placeholder="Notes"></textarea>
            </div>
            <div class="clear"></div>
            
        </fieldset>
        <a href="javascript:" class="delete-record delete-specific button-action">Delete this project</a>
        <button type="button" class="button button-primary" name="btn_save" id="btn_save">Submit</button>
        <button type="button" class="button button-cancel" name="btn_cancel" id="btn_cancel">Cancel</button>
    </form>
</div>
<button data-modal-id="hasan" type="button" class="button button-secondary open-modal" name="btn_add" id="btn_add"><i class="fa fa-plus-square-o" aria-hidden="true"></i>
 Add New</button>

<table id="projects_dt" class="display dataTable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th data-id=""></th>
            <th>Project ID</th>
            <th>Client Name</th>
            <th>Name</th>
            <th>Active</th>
            <th>Code</th>
            <th>Billable</th>
            <th>Starts On</th>
            <th>Ends On</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody>
       <?php foreach($projects as $p){ ?>
            <tr>
                <td align="center" style="width: 100px; height: 50px;">
                    <a title="Edit" class="edit-record button-action" data-id="<?=$p->project->id?>" href="javascript:">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a title="Delete" class="delete-record button-action" data-id="<?=$p->project->id?>" href="javascript:">
                        <i class="fa fa-trash"></i>
                    </a>
                    <div class="clear"></div>
                </td>
                <td><?=$p->project->id?></td>
                <td><?=getClientNameFromSession($p->project->client_id)?></td>
                <td><?=$p->project->name?></td>
                <td align="center">
                    <a class="change-status status-<?=$p->project->active?"green":"gray"?>" href="javascript:" data-id="<?=$p->project->id?>"><i class="fa"></i>
                    </a>
                </td>
                <td><?=$p->project->code?></td>
                <td><?=$p->project->billable?></td>
                <td><?=$p->project->starts_on?></td>
                <td><?=$p->project->ends_on?></td>
                <td><?=$p->project->updated_at?></td>
            </tr>
        <? }?>
    </tbody>
</table>
<!--- Third party libraries -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/dcalendar.picker.css" />
<script type="text/javascript" src="<?=base_url()?>static/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/projects.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/dcalendar.picker.js"></script>
