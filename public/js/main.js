/*Functions for use bootstrap table*/
function queryParams() {
    return {
        type: 'owner',
        sort: 'updated',
        direction: 'desc',
        per_page: 100,
        page: 1
    };
}
function formaterColumnEditActions(value, row, index) {
    console.log(this);
    var controller=this.field;
    var html = [];
   html.push('<div class="btn-group">');
    html.push('');
    //html.push('<a href="'+controller+'/delete/'+row.id+'" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a> ');
    html.push( '<form method="POST" action="'+
        controller+'/'+row.id+'" class="form-inline" >' + '<input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="'+$('meta[name="csrf-token"]').attr('content')+'">'+ '<a href="'+controller+'/editar/'+row.id+'" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> <button class="btn btn-xs btn-danger" type="submit" v><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+ '</form>');
    html.push('</div>');
    return html.join('');
}
function formaterColumnColor(value, row, index, field) {
  return {
    css: {"background-color": row.color}
  };
}
//# sourceMappingURL=main.js.map
