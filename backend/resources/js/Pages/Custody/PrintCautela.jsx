import { Head } from '@inertiajs/react';
import { useEffect } from 'react';

export default function PrintCautela({ custodyLog }) {
    useEffect(() => {
        // Auto-print quando o componente carrega
        const timer = setTimeout(() => {
            window.print();
        }, 500);

        return () => clearTimeout(timer);
    }, []);

    const formatDate = (dateString) => {
        if (!dateString) return '';
        return new Date(dateString).toLocaleDateString('pt-BR');
    };

    const formatCurrency = (value) => {
        if (!value) return 'R$ 0,00';
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    };

    return (
        <>
            <Head title={`Cautela ${custodyLog.cautela_number} - SGAITI`} />
            
            <div className="print-container">
                <style jsx>{`
                    @media print {
                        body { margin: 0; padding: 0; }
                        .print-container { 
                            width: 100%; 
                            max-width: none; 
                            margin: 0; 
                            padding: 20mm;
                            font-family: 'Times New Roman', serif;
                            font-size: 12pt;
                            line-height: 1.4;
                        }
                        .no-print { display: none !important; }
                        .page-break { page-break-before: always; }
                        .signature-area { 
                            border-top: 1px solid #000; 
                            margin-top: 40px; 
                            padding-top: 10px; 
                        }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { 
                            border: 1px solid #000; 
                            padding: 8px; 
                            text-align: left; 
                            vertical-align: top;
                        }
                        .header-logo { 
                            text-align: center; 
                            margin-bottom: 20px; 
                            border-bottom: 2px solid #000;
                            padding-bottom: 15px;
                        }
                        .title { 
                            font-size: 16pt; 
                            font-weight: bold; 
                            text-align: center; 
                            margin: 20px 0;
                            text-transform: uppercase;
                        }
                        .section-title {
                            font-size: 14pt;
                            font-weight: bold;
                            margin: 15px 0 10px 0;
                            text-transform: uppercase;
                            border-bottom: 1px solid #000;
                            padding-bottom: 5px;
                        }
                    }
                    
                    @media screen {
                        .print-container { 
                            max-width: 210mm; 
                            margin: 20px auto; 
                            padding: 20mm;
                            background: white;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        }
                    }
                `}</style>

                {/* Header Oficial */}
                <div className="header-logo">
                    <div style={{ fontSize: '18pt', fontWeight: 'bold', marginBottom: '10px' }}>
                        COMANDO DA AERONÁUTICA
                    </div>
                    <div style={{ fontSize: '14pt', fontWeight: 'bold', marginBottom: '5px' }}>
                        GRUPAMENTO DE APOIO CAMPO GRANDE
                    </div>
                    <div style={{ fontSize: '12pt', marginBottom: '5px' }}>
                        SEÇÃO DE TECNOLOGIA DA INFORMAÇÃO
                    </div>
                    <div style={{ fontSize: '10pt', color: '#666' }}>
                        Sistema de Gestão de Ativos de TI - SGAITI
                    </div>
                </div>

                {/* Título do Documento */}
                <div className="title">
                    TERMO DE RESPONSABILIDADE - CAUTELA DE MATERIAL
                </div>

                {/* Informações da Cautela */}
                <div style={{ marginBottom: '20px' }}>
                    <table style={{ marginBottom: '15px' }}>
                        <tbody>
                            <tr>
                                <td style={{ fontWeight: 'bold', width: '30%' }}>Nº da Cautela:</td>
                                <td style={{ fontWeight: 'bold', fontSize: '14pt' }}>{custodyLog.cautela_number}</td>
                            </tr>
                            <tr>
                                <td style={{ fontWeight: 'bold' }}>Data de Abertura:</td>
                                <td>{formatDate(custodyLog.checkout_date)}</td>
                            </tr>
                            <tr>
                                <td style={{ fontWeight: 'bold' }}>Status:</td>
                                <td style={{ fontWeight: 'bold', color: custodyLog.checkin_date ? '#d97706' : '#059669' }}>
                                    {custodyLog.checkin_date ? 'CONCLUÍDA' : 'ATIVA'}
                                </td>
                            </tr>
                            {custodyLog.checkin_date && (
                                <tr>
                                    <td style={{ fontWeight: 'bold' }}>Data de Devolução:</td>
                                    <td>{formatDate(custodyLog.checkin_date)}</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Dados do Responsável */}
                <div className="section-title">Dados do Militar Responsável</div>
                <table style={{ marginBottom: '20px' }}>
                    <tbody>
                        <tr>
                            <td style={{ fontWeight: 'bold', width: '25%' }}>Nome Completo:</td>
                            <td style={{ fontWeight: 'bold' }}>{custodyLog.user?.name || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td style={{ fontWeight: 'bold' }}>Posto/Graduação:</td>
                            <td>{custodyLog.user?.rank || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td style={{ fontWeight: 'bold' }}>ID Militar:</td>
                            <td>{custodyLog.user?.military_id || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td style={{ fontWeight: 'bold' }}>Setor:</td>
                            <td>{custodyLog.user?.sector?.name || 'N/A'}</td>
                        </tr>
                        {custodyLog.user?.email && (
                            <tr>
                                <td style={{ fontWeight: 'bold' }}>E-mail:</td>
                                <td>{custodyLog.user.email}</td>
                            </tr>
                        )}
                    </tbody>
                </table>

                {/* Lista de Ativos */}
                <div className="section-title">Relação de Material Sob Cautela</div>
                <table style={{ marginBottom: '30px' }}>
                    <thead>
                        <tr style={{ backgroundColor: '#f3f4f6' }}>
                            <th style={{ width: '8%' }}>Item</th>
                            <th style={{ width: '15%' }}>QR Code</th>
                            <th style={{ width: '35%' }}>Descrição do Material</th>
                            <th style={{ width: '15%' }}>Nº Série</th>
                            <th style={{ width: '12%' }}>Valor (R$)</th>
                            <th style={{ width: '15%' }}>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {custodyLog.assets?.map((asset, index) => (
                            <tr key={asset.id}>
                                <td style={{ textAlign: 'center', fontWeight: 'bold' }}>
                                    {String(index + 1).padStart(2, '0')}
                                </td>
                                <td style={{ fontFamily: 'monospace', fontSize: '10pt' }}>
                                    {asset.qr_code}
                                </td>
                                <td>{asset.name}</td>
                                <td style={{ fontFamily: 'monospace', fontSize: '10pt' }}>
                                    {asset.serial_number || '-'}
                                </td>
                                <td style={{ textAlign: 'right' }}>
                                    {formatCurrency(asset.purchase_value)}
                                </td>
                                <td style={{ fontSize: '10pt' }}>
                                    {asset.notes || '-'}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                    <tfoot>
                        <tr style={{ backgroundColor: '#f9fafb', fontWeight: 'bold' }}>
                            <td colSpan="4" style={{ textAlign: 'right', padding: '12px' }}>
                                TOTAL DE ITENS: {custodyLog.assets?.length || 0}
                            </td>
                            <td style={{ textAlign: 'right', padding: '12px' }}>
                                {formatCurrency(
                                    custodyLog.assets?.reduce((sum, asset) => 
                                        sum + (parseFloat(asset.purchase_value) || 0), 0
                                    ) || 0
                                )}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                {/* Observações */}
                {custodyLog.notes && (
                    <div style={{ marginBottom: '30px' }}>
                        <div className="section-title">Observações Gerais</div>
                        <div style={{ 
                            border: '1px solid #000', 
                            padding: '15px', 
                            minHeight: '60px',
                            backgroundColor: '#fafafa'
                        }}>
                            {custodyLog.notes}
                        </div>
                    </div>
                )}

                {/* Termos de Responsabilidade */}
                <div className="section-title">Termos de Responsabilidade</div>
                <div style={{ fontSize: '11pt', textAlign: 'justify', marginBottom: '30px', lineHeight: '1.6' }}>
                    <p style={{ marginBottom: '12px' }}>
                        <strong>1.</strong> Declaro ter recebido em perfeito estado de conservação e funcionamento os materiais 
                        relacionados neste termo, comprometendo-me a utilizá-los exclusivamente para fins de serviço.
                    </p>
                    <p style={{ marginBottom: '12px' }}>
                        <strong>2.</strong> Responsabilizo-me pela guarda, conservação e bom uso dos materiais, devendo 
                        comunicar imediatamente qualquer avaria, perda ou furto à Seção de Tecnologia da Informação.
                    </p>
                    <p style={{ marginBottom: '12px' }}>
                        <strong>3.</strong> Comprometo-me a devolver os materiais nas mesmas condições de recebimento, 
                        quando solicitado ou ao cessar a necessidade de uso.
                    </p>
                    <p style={{ marginBottom: '12px' }}>
                        <strong>4.</strong> Estou ciente de que a não devolução ou danos causados por mau uso 
                        resultarão em responsabilização disciplinar e/ou pecuniária conforme regulamentação vigente.
                    </p>
                    <p>
                        <strong>5.</strong> Este termo substitui qualquer cautela anterior referente aos materiais 
                        aqui relacionados.
                    </p>
                </div>

                {/* Área de Assinaturas */}
                <div style={{ marginTop: '50px' }}>
                    <table>
                        <tbody>
                            <tr>
                                <td style={{ width: '50%', textAlign: 'center', padding: '40px 20px' }}>
                                    <div style={{ borderTop: '1px solid #000', paddingTop: '10px', marginTop: '60px' }}>
                                        <strong>{custodyLog.user?.rank} {custodyLog.user?.name}</strong><br />
                                        <span style={{ fontSize: '10pt' }}>RESPONSÁVEL PELO MATERIAL</span><br />
                                        <span style={{ fontSize: '10pt' }}>ID: {custodyLog.user?.military_id}</span>
                                    </div>
                                </td>
                                <td style={{ width: '50%', textAlign: 'center', padding: '40px 20px' }}>
                                    <div style={{ borderTop: '1px solid #000', paddingTop: '10px', marginTop: '60px' }}>
                                        <strong>SEÇÃO DE TECNOLOGIA DA INFORMAÇÃO</strong><br />
                                        <span style={{ fontSize: '10pt' }}>RESPONSÁVEL PELA ENTREGA</span><br />
                                        <span style={{ fontSize: '10pt' }}>SGTI-GAC</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* Rodapé */}
                <div style={{ 
                    marginTop: '40px', 
                    textAlign: 'center', 
                    fontSize: '9pt', 
                    color: '#666',
                    borderTop: '1px solid #ccc',
                    paddingTop: '15px'
                }}>
                    <p>
                        Documento gerado automaticamente pelo Sistema SGAITI em {new Date().toLocaleString('pt-BR')}
                    </p>
                    <p>
                        Cautela Nº {custodyLog.cautela_number} | Comando da Aeronáutica - GAC/MS
                    </p>
                </div>

                {/* Botão para voltar (apenas na tela) */}
                <div className="no-print" style={{ 
                    position: 'fixed', 
                    top: '20px', 
                    right: '20px', 
                    zIndex: 1000 
                }}>
                    <button
                        onClick={() => window.history.back()}
                        style={{
                            padding: '10px 20px',
                            backgroundColor: '#3b82f6',
                            color: 'white',
                            border: 'none',
                            borderRadius: '5px',
                            cursor: 'pointer',
                            fontSize: '14px'
                        }}
                    >
                        ← Voltar
                    </button>
                </div>
            </div>
        </>
    );
}