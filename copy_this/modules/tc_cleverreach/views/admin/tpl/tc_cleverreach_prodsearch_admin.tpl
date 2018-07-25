[{assign var="isOAuthTokenValid" value=$oView->tc_isOAuthTokenValid()}]
<script src="[{ $oView->getCleverreachModuleUrl('out/src/js/lib/jquery.min.js') }]"></script>
<script src="[{ $oView->getCleverreachModuleUrl('out/src/js/lib/bootstrap.min.js') }]"></script>
<link rel="stylesheet" href="[{ $oView->getCleverreachModuleUrl('out/src/css/tc_cleverreach_style.css') }]">
<script type="text/javascript">
    <!--
    window.onload = function () {
        top.reloadEditFrame();
    }

    //-->
    function deleteEntry(id) {
        var oForm = document.getElementById("myedit");
        oForm.fnc.value = 'deleteEntry';
        oForm.prodsearchid.value = id;
        oForm.submit();
    }

    function saveElementEntry() {
        var oForm = document.getElementById("element");
        oForm.fnc.value = 'saveElementEntry';
        oForm.submit();
    }


</script>
<style type="text/css">
    .tc_div_paths {
        border: 1px solid lightgrey;
        padding: 10px 8px;
        margin-bottom: 15px;
        margin-top: 10px;
    }

    .box {
        background-image: url("[{ $oViewConf->getModuleUrl('tc_cleverreach', 'out/src/img/tc_logo.jpg')}]");
        background-position: left bottom;
        background-repeat: no-repeat;
    }

    .tc_input_field {
        /*        width: 250px;*/
    }

    .tc_td_config_name {
        width: 130px;
    }

    .box {
        background-image: url("[{ $oViewConf->getModuleUrl('tc_cleverreach', 'out/src/img/tc_logo.jpg')}]");
        background-position: left bottom;
        background-repeat: no-repeat;
    }

    .tc-cleverreach-title {
        margin: 0 0 10px 0;
    }

</style>

[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="0">
    <input type="hidden" name="cl" value="tc_cleverreach_prodsearch_admin">
</form>
<div class="container">
    [{if !$isOAuthTokenValid }]
        <span>[{oxmultilang ident="TC_CLEVERREACH_OAUTH_NEEDED_HINT"}]</span>
    [{else}]
        <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="oxid" value="0">
            <input type="hidden" name="cl" value="tc_cleverreach_prodsearch_admin">
            <input type="hidden" name="fnc" value="saved">
            <input type="hidden" name="prodsearchid" value="">

            <h2>[{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH"}]</h2>
            <p>[{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH_DESC"}]</p>

            [{assign var="editobj" value=$oView->getEditObj()}]

            <input type="hidden" name="editval[url]" type="text" value="[{$oView->getUrl()}]"
                   class="tc_input_field" size="100" DISABLED/>

            <div class="form-group row">
                <div class="col-xs-4">
                    <label for="name">[{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH_NAME"}]</label>
                    <input class="form-control" id="name" type="text" name="editval[name]"
                           value="[{$oView->getName()}]">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-xs-4">
                    <label for="pass">[{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH_PASSWORD"}]</label>
                    <input class="form-control" id="pass" type="text" name="editval[password]"
                           value="[{$oView->getPassword()}]">
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-success"
                        value="[{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH_SUBMITANDCREATE"}]">
                    [{oxmultilang ident="TC_CLEVERREACH_PRODSEARCH_SUBMITANDCREATE"}]
                </button>
                <span>[{ oxinputhelp ident="TC_CLEVERREACH_PRODSEARCH_HELP_CREATE" }]</span>
            </div>
        </form>
    [{/if}]
    [{*oxstyle*}]
</div>
[{include file="bottomitem.tpl"}]