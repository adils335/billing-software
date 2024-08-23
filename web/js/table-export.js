$(function() {
    var $table = $('#table')
    function initTable() {
      $table.bootstrapTable('destroy').bootstrapTable({
        exportTypes: [ 'csv', 'excel', 'pdf'],
        locale: "en-US",
      })
    }
    initTable()
})