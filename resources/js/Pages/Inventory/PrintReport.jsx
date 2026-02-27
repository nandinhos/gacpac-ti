import { Head } from '@inertiajs/react';

export default function PrintReport({ inventory, foundAssets = [], pendingAssets = [], uncataloguedItems = [] }) {
    const totalAssets = foundAssets.length + pendingAssets.length;
    const foundPercentage = totalAssets > 0 ? ((foundAssets.length / totalAssets) * 100).toFixed(1) : 0;
    const currentDate = new Date().toLocaleDateString('pt-BR');

    return (
        <>
            <Head title={`Relatório - Inventário ${inventory.commission_number || 'Sem Comissão'}`} />
            
            <div className="print-container" style={{ 
                fontFamily: 'Arial, sans-serif', 
                fontSize: '12px', 
                color: '#000',
                backgroundColor: '#fff',
                padding: '20px',
                maxWidth: '210mm',
                margin: '0 auto'
            }}>
                
                {/* Cabeçalho Oficial */}
                <div style={{ textAlign: 'center', marginBottom: '30px', borderBottom: '2px solid #000', paddingBottom: '15px' }}>
                    <h1 style={{ fontSize: '18px', fontWeight: 'bold', margin: '0 0 5px 0' }}>
                        COMANDO DA AERONÁUTICA
                    </h1>
                    <h2 style={{ fontSize: '16px', fontWeight: 'bold', margin: '0 0 5px 0' }}>
                        SISTEMA DE GESTÃO DE TI DO GAC-PAC
                    </h2>
                    <h3 style={{ fontSize: '14px', fontWeight: 'bold', margin: '0' }}>
                        RELATÓRIO DE INVENTÁRIO
                    </h3>
                </div>

                {/* Informações do Inventário */}
                <div style={{ marginBottom: '25px' }}>
                    <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                        DADOS DO INVENTÁRIO
                    </h4>
                    <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '12px' }}>
                        <tbody>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold', width: '30%' }}>Número da Comissão:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999' }}>
                                    {inventory.commission_number || 'Conferência Inopinada'}
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold' }}>Setor:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999' }}>
                                    {inventory.sector?.name}
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold' }}>Responsável:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999' }}>
                                    {inventory.responsible_user?.name} - {inventory.responsible_user?.rank}
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold' }}>Data de Início:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999' }}>
                                    {new Date(inventory.start_date).toLocaleDateString('pt-BR')}
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold' }}>Data de Conclusão:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999' }}>
                                    {inventory.end_date ? new Date(inventory.end_date).toLocaleDateString('pt-BR') : 'Em andamento'}
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: '5px', fontWeight: 'bold' }}>Status:</td>
                                <td style={{ padding: '5px', borderBottom: '1px dotted #999', fontWeight: 'bold' }}>
                                    {inventory.status}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* Resumo Estatístico */}
                <div style={{ marginBottom: '25px' }}>
                    <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                        RESUMO ESTATÍSTICO
                    </h4>
                    <table style={{ width: '100%', borderCollapse: 'collapse', border: '1px solid #000' }}>
                        <thead>
                            <tr style={{ backgroundColor: '#f0f0f0' }}>
                                <th style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>Categoria</th>
                                <th style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>Quantidade</th>
                                <th style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style={{ border: '1px solid #000', padding: '8px', fontWeight: 'bold' }}>Itens Encontrados</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{foundAssets.length}</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{foundPercentage}%</td>
                            </tr>
                            <tr>
                                <td style={{ border: '1px solid #000', padding: '8px', fontWeight: 'bold' }}>Itens Pendentes</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{pendingAssets.length}</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{(100 - foundPercentage).toFixed(1)}%</td>
                            </tr>
                            <tr>
                                <td style={{ border: '1px solid #000', padding: '8px', fontWeight: 'bold' }}>Itens Não Catalogados</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{uncataloguedItems.length}</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>-</td>
                            </tr>
                            <tr style={{ backgroundColor: '#f0f0f0', fontWeight: 'bold' }}>
                                <td style={{ border: '1px solid #000', padding: '8px' }}>TOTAL DE ITENS</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>{totalAssets}</td>
                                <td style={{ border: '1px solid #000', padding: '8px', textAlign: 'center' }}>100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* Itens Encontrados */}
                {foundAssets.length > 0 && (
                    <div style={{ marginBottom: '25px' }}>
                        <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                            ITENS ENCONTRADOS ({foundAssets.length})
                        </h4>
                        <table style={{ width: '100%', borderCollapse: 'collapse', border: '1px solid #000', fontSize: '10px' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#e8f5e8' }}>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'left' }}>Descrição</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>QR Code</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>Nº Série</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>Marca/Modelo</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foundAssets.map((asset, index) => (
                                    <tr key={index}>
                                        <td style={{ border: '1px solid #000', padding: '5px' }}>{asset.name}</td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center', fontFamily: 'monospace' }}>
                                            {asset.qr_code}
                                        </td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>
                                            {asset.serial_number || '-'}
                                        </td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>
                                            {asset.brand} {asset.model}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}

                {/* Itens Pendentes */}
                {pendingAssets.length > 0 && (
                    <div style={{ marginBottom: '25px' }}>
                        <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                            ITENS PENDENTES ({pendingAssets.length})
                        </h4>
                        <table style={{ width: '100%', borderCollapse: 'collapse', border: '1px solid #000', fontSize: '10px' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#ffe8e8' }}>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'left' }}>Descrição</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>QR Code</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>Nº Série</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>Marca/Modelo</th>
                                </tr>
                            </thead>
                            <tbody>
                                {pendingAssets.map((asset, index) => (
                                    <tr key={index}>
                                        <td style={{ border: '1px solid #000', padding: '5px' }}>{asset.name}</td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center', fontFamily: 'monospace' }}>
                                            {asset.qr_code}
                                        </td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>
                                            {asset.serial_number || '-'}
                                        </td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>
                                            {asset.brand} {asset.model}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}

                {/* Itens Não Catalogados */}
                {uncataloguedItems.length > 0 && (
                    <div style={{ marginBottom: '25px' }}>
                        <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                            ITENS NÃO CATALOGADOS ({uncataloguedItems.length})
                        </h4>
                        <table style={{ width: '100%', borderCollapse: 'collapse', border: '1px solid #000', fontSize: '10px' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#e8f0ff' }}>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'left' }}>Nº</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'left' }}>Descrição</th>
                                    <th style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>Data de Encontro</th>
                                </tr>
                            </thead>
                            <tbody>
                                {uncataloguedItems.map((item, index) => (
                                    <tr key={index}>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>{index + 1}</td>
                                        <td style={{ border: '1px solid #000', padding: '5px' }}>
                                            {typeof item === 'string' ? item : item.description}
                                        </td>
                                        <td style={{ border: '1px solid #000', padding: '5px', textAlign: 'center' }}>
                                            {item.created_at ? new Date(item.created_at).toLocaleDateString('pt-BR') : currentDate}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}

                {/* Observações */}
                {inventory.notes && (
                    <div style={{ marginBottom: '25px' }}>
                        <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '10px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                            OBSERVAÇÕES GERAIS
                        </h4>
                        <div style={{ border: '1px solid #000', padding: '10px', minHeight: '40px', backgroundColor: '#f9f9f9' }}>
                            <p style={{ margin: '0', whiteSpace: 'pre-wrap' }}>{inventory.notes}</p>
                        </div>
                    </div>
                )}

                {/* Espaço para Assinaturas */}
                <div style={{ marginTop: '40px', pageBreakInside: 'avoid' }}>
                    <h4 style={{ fontSize: '14px', fontWeight: 'bold', marginBottom: '30px', borderBottom: '1px solid #ccc', paddingBottom: '5px' }}>
                        ASSINATURAS
                    </h4>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '50px' }}>
                        <div style={{ textAlign: 'center', width: '45%' }}>
                            <div style={{ borderTop: '1px solid #000', paddingTop: '5px', marginTop: '40px' }}>
                                <strong>{inventory.responsible_user?.name}</strong><br />
                                {inventory.responsible_user?.rank}<br />
                                Responsável pelo Inventário
                            </div>
                        </div>
                        <div style={{ textAlign: 'center', width: '45%' }}>
                            <div style={{ borderTop: '1px solid #000', paddingTop: '5px', marginTop: '40px' }}>
                                <strong>___________________________</strong><br />
                                Fiscal/Supervisor<br />
                                Data: ___/___/______
                            </div>
                        </div>
                    </div>
                </div>

                {/* Rodapé */}
                <div style={{ marginTop: '30px', textAlign: 'center', fontSize: '10px', color: '#666', borderTop: '1px solid #ccc', paddingTop: '10px' }}>
                    <p style={{ margin: '0' }}>
                        Relatório gerado automaticamente pelo SGTI-GAC em {currentDate} às {new Date().toLocaleTimeString('pt-BR')}
                    </p>
                </div>

            </div>

            {/* CSS para impressão */}
            <style jsx>{`
                @media print {
                    body { margin: 0; padding: 0; }
                    .print-container { 
                        margin: 0; 
                        padding: 15mm; 
                        box-shadow: none; 
                        max-width: none;
                        width: 100%;
                    }
                    @page { 
                        margin: 10mm; 
                        size: A4; 
                    }
                }
                @media screen {
                    .print-container {
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        margin: 20px auto;
                    }
                }
            `}</style>
        </>
    );
}