{if isset($successes)}

  {foreach from=$successes item=success}
    <div class="alert alert-success" role="alert">
      {$success}
    </div>
  {/foreach}





{/if}
<div class="panel" id="fieldset_0">
  <form class="defaultForm form-horizontal" method="post" action="{$current_url}">
    <div class="panel-heading">
      <i class="icon-cogs"></i> Actualizar precios de los productos
    </div>
    <div class="form-wrapper">
      <p>Se actualizaran los precios de los productos en base a el precio/gr del metal insertado en el módulo.</p>
    </div>
    <div class="panel-footer">
      <button type="submit" value="1" id="submit_cot_metales" name="submit_cot_metales_update_products" class="btn btn-default pull-right">
        <i class="process-icon-save"></i> Actualizar
      </button>
    </div>
  </form>
</div>

<div class="panel" id="fieldset_0">
  <form class="defaultForm form-horizontal" method="post" action="{$current_url}">
    <div class="panel-heading">
      <i class="icon-cogs"></i> Configuración de los valores de los metales
    </div>
    <div class="form-wrapper">
      {foreach from=$metales item=metal}
        <div class="form-group">
          <label class="control-label col-lg-3">{$metal.cot_metal_name}</label>
          <div class="col-lg-3">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="icon icon-tag"></i>
              </span>
              <input type="text" value="{$metal.cot_metal_precio}" name="cot_metal_id_{$metal.cot_metal_id}" />
            </div>
          </div>
        </div>
      {/foreach}
    </div>
    <div class="panel-footer">
      <button type="submit" value="1" id="submit_cot_metales" name="submit_cot_metales" class="btn btn-default pull-right">
        <i class="process-icon-save"></i> Guardar
      </button>
    </div>
  </form>
</div>




{*
<div class="panel" id="fieldset_1">
<form class="defaultForm form-horizontal" method="post" action="{$current_url}">
<div class="panel-heading">
<i class="icon-cogs"></i> Insertar nuevos metales
</div>
<div class="form-wrapper">
<div class="form-group">
<label class="control-label col-lg-3">Nombre del nuevo metal</label>
<div class="col-lg-3">
<div class="input-group">
<span class="input-group-addon">
<i class="icon icon-tag"></i>
</span>
<input type="text" value="" name="cot_metal_new_name" />
</div>
</div>
</div>
<div class="form-group">
<label class="control-label col-lg-3">Precio gr/€</label>
<div class="col-lg-3">
<div class="input-group">
<span class="input-group-addon">
<i class="icon icon-tag"></i>
</span>
<input type="text" value="0.00" name="cot_metal_new_precio" />
</div>
</div>
</div>
</div>
<div class="panel-footer">
<button type="submit" value="1" id="submit_cot_metales" name="submit_cot_metales_new" class="btn btn-default pull-right">
<i class="process-icon-save"></i> Guardar
</button>
</div>
</form>
</div>

<div class="panel" id="fieldset_1">
<form class="defaultForm form-horizontal" method="post" action="{$current_url}">
<div class="panel-heading">
<i class="icon-cogs"></i> Elimina un metal
</div>
<div class="form-wrapper">
<div class="form-group">
<label class="control-label col-lg-3">Metal</label>
<div class="col-lg-3">
<select name="cot_metal_id_to_delete">
{foreach from=$metales item=metal}
<option value="{$metal.cot_metal_id}">{$metal.cot_metal_name}</option>
{/foreach}
</select>
</div>
</div>
</div>
<div class="panel-footer">
<button type="submit" value="1" id="submit_cot_metales" name="submit_cot_metales_delete" class="btn btn-default pull-right">
<i class="process-icon-save"></i> Eliminar
</button>
</div>
</form>
</div>

<input type="hidden" value="{$simple_path.locale|escape:'html':'UTF-8'}" name="locale" />  
<input type="hidden" value="{$simple_path.spId|escape:'html':'UTF-8'}" name="spId" />  
<input type="hidden" value="{$simple_path.uniqueId|escape:'html':'UTF-8'}" name="uniqueId" />  
<input type="hidden" value="{$simple_path.allowedLoginDomains|escape:'html':'UTF-8'}" name="allowedLoginDomains[]" />
{foreach from=$simple_path.loginRedirectURLs_1 item=splr}
<input type="hidden" value="{$splr|escape:'html':'UTF-8'}" name="loginRedirectURLs[]" />
{/foreach}

<input type="button" name="submit_cot_metales" id="submit_cot_metales" value="Aceptar" />*}