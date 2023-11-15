
  // Função para criar um novo registro
  function createData(url, data) {
    showLoader();
    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData:false,
      success: function(response) {
        showSuccessMessage('Registro criado com sucesso!');
        hideLoader();
      },
      error: function(error) {
        console.error('Erro ao criar registro:', error);
        hideLoader();
        showErrorMessage("Não foi possivel criar o registro");
      }
    });
  }

  // Função para atualizar um registro existente
  function updateData(url, data) {
    showLoader();
    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData:false,
      success: function(response) {
        showSuccessMessage('Registro atualizado com sucesso!');
        hideLoader();
      },
      error: function(error) {
        console.error('Erro ao atualizar registro:', error);
        hideLoader();
      }
    });
  }

    // Função para exibir um registro
    function updateDataWithData(url, data) {
      showLoader();
      return $.ajax({
        url: url,
        method:'POST',
        data: data,
        contentType: false,
        cache: false,
        processData:false,
        dataType: 'json',
      }).catch(function(error){
        console.error('Erro ao obter registro:', error);
        hideLoader();
      });
    }

  // Função para exibir um registro
  function showData(url) {
    showLoader();
    return $.ajax({
      url: url,
      method:'GET',
      processData: false,
      dataType: 'json',
    }).catch(function(error){
      console.error('Erro ao obter registro:', error);
      hideLoader();
    });
  }

  // Função para exibir um registro
  function showDataWithData(url, data) {
    showLoader();
    return $.ajax({
      url: url,
      method: 'POST',
      data: data,
      dataType: 'JSON',
      processData: false,
      contentType: false,
      cache: false,
      }).catch(function(error){
        console.error('Erro ao obter registro:', error);
        hideLoader();
      });
  }

  function redirecionarPagina(url) {
    window.location.assign(url);
  }

  // Função para excluir um registro
  function deleteData(url) {
    showLoader();
    $.ajax({
      url: url,
      method: 'DELETE',
      processData: false,
      dataType: 'json',
      success: function(response) {
        showSuccessMessage('Registro excluído com sucesso!');
        hideLoader();
      },
      error: function(error) {
        console.error('Erro ao excluir registro:', error);
        hideLoader();
      }
    });
  }

  function showLoader() {
    $('<div class="spinner"></div>').appendTo('body');
  }

  function hideLoader() {
    $('.spinner').remove();
  }

  function showSuccessMessage(message) {
    Swal.fire({
      icon: 'success',
      title: 'Sucesso!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function showWarningMessage(message) {
    Swal.fire({
      icon: 'warning',
      title: 'Atenção!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function showErrorMessage(message) {
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function refreshPage() {
    location.reload(true);
  }
  
  
  function formatDate(value)
  {
      const date = value.split('-');
      return ''+date[2]+ '/' + date[1] + '/' + date[0];
  }

  function formatDateWithHour(value)
  {
      const date = value.split(' ');
      return formatDate(date[0]) + ' ' + date[1];
  }

  function prepareTipo(value)
  {
      var res = "";
      switch (value) {
          case 1:
              res = "Dinheiro";
          break;
          case 2:
              res = "Cartão de Crédito"
          break;
          case 3:
             res =  "Cartão de Débito"
          break;
          case 4:
             res =  "Déposito/PIX"
          break;
      }

      return res;
  }

  function prepareStatus(value)
  {
      var res = "";
      switch (value) {
          case '1':
              res = "Reservada";
          break;
          case '2':
              res = "Confirmada"
          break;
          case '3':
             res =  "Hospedada"
          break;
          case '4':
             res =  "Finalizada"
          break;
          case '5':
            res =  "Cancelada"
          break;
      }

      return res;
  }

  function calculaPagamento(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += parseFloat(element.valorPagamento);
        });

        return valor;
    }

    function prepareSelect(data, select_id, selected = '')
    {
        console.log(selected);
        $(select_id).selectize()[0].selectize.destroy();
        let $select = $(select_id).selectize({
            maxItems: 1,
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            options: 
               data,
                create: true,
        });

        var control = $select[0].selectize;
        control.setValue(selected);
    }

    function imprimir() {
      var conteudoDiv = document.getElementById("contents_inputs").innerHTML;
      var janela = window.open('', '', 'width=600,height=600');
      janela.document.write('<html><head><title>Impressão</title><style type="text/css" media="print">' +
      'body { margin: 0;padding: 0;} table {width: 100%; border-collapse: collapse;} table, th, td { border: 1px solid #ccc;}' + 
      'th, td {padding: 8px;text-align: left;}  tr td:last-child {display: none;}</style></head><body>');
      janela.document.write(conteudoDiv);
      janela.document.write('</body></html>');
      janela.print();
      janela.close();
  }