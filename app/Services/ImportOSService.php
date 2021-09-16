<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Services\UtilImportOSService;

class ImportOSService
{
    const referenciaTxtId = 'REFERENCIA';
    const clienteTxtId = 'NOME CLIENTE';
    const cidadeTxtId = 'CIDADE / UF';
    const valorTxtId = 'VR PREV SERVICO';
    const deslocamentoTxtId = 'VR PREV DESLOC';
    const dataEmissaoTxtId = 'DATA : ';
    const horaEmissaoTxtId = 'HORA : ';
    const prazoTxtId = 'PRAZO EXECUCAO';
    const servicoTxtIdInicio = 'ASSUNTO';
    const servicoTxtIdFim = 'ATIVIDADE';

     public function returnDataOSFromTXT($lines) {
        
        $data = [];

        // Reading a .txt file line by line
        foreach ($lines as $line) {

            if(strpos($line, self::referenciaTxtId) !== false)
                $data['code'] = UtilImportOSService::readReferencia($line);

            if(strpos($line, self::clienteTxtId) !== false)
                //$data['client_name'] = UtilImportOSService::readCliente($line);
                $data['client_name'] = 'Caixa';

            if(strpos($line, self::cidadeTxtId) !== false)
                $data['city_name'] = UtilImportOSService::readCidade($line);

            if(strpos($line, self::cidadeTxtId) !== false)
                $data['uf_name'] = UtilImportOSService::readUf($line);

            if(strpos($line, self::valorTxtId) !== false)
                $data['amount'] = UtilImportOSService::readValor($line);

            if(strpos($line, self::deslocamentoTxtId) !== false)
                $data['displacement'] = UtilImportOSService::readDeslocalmento($line);

            if(strpos($line, self::dataEmissaoTxtId) !== false &&
                    strpos($line, self::horaEmissaoTxtId) !== false)
                $data['published_at'] = UtilImportOSService::readDataEmissao($line);

            if(strpos($line, self::prazoTxtId) !== false)
                $data['deadline'] = UtilImportOSService::readPrazo($line) + 1;

            if(strpos($line, self::servicoTxtIdInicio) !== false)
                $data['service_name'] = UtilImportOSService::readServico($line);

            if(strpos($line, self::servicoTxtIdFim) !== false)
                $data['service_name'] .= ' - '.UtilImportOSService::readServico($line);
        }

        return $data;
    }

}
