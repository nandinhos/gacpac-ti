import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import ConfirmationModal from '@/Components/ConfirmationModal';
import SignedDocumentViewer from '@/Components/SignedDocumentViewer';

export default function Show({ log }) {
    const [showDischargeModal, setShowDischargeModal] = useState(false);
    const [custodyLog, setCustodyLog] = useState(log);

    const handleDischarge = () => {
        setShowDischargeModal(true);
    };

    const handleConfirmDischarge = (justification) => {
        router.put(route('custody.checkin', log.id), {
            checkinDate: new Date().toISOString().split('T')[0],
            justification: justification
        }, {
            preserveScroll: true,
        });
    };

    const handleDocumentUploaded = (documentUrl) => {
        // Abordagem simples: recarregar a página inteira para garantir sincronização
        window.location.reload();
    };

    const handleDocumentRemoved = () => {
        // Abordagem simples: recarregar a página inteira para garantir sincronização
        window.location.reload();
    };

    const handleExportPDF = () => {
        // Abrir em nova aba para impressão/PDF
        window.open(route('custody.print', log.id), '_blank');
    };

    const status = custodyLog.checkin_date ? 'Concluída' : 'Ativa';

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 flex items-center">
                        <svg className="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Detalhes da Cautela: {custodyLog.cautela_number}
                    </h2>
                    <div className="flex space-x-3">
                        <button
                            onClick={handleExportPDF}
                            className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors flex items-center"
                        >
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar PDF
                        </button>
                        <Link 
                            href={route('custody.index')} 
                            className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors flex items-center"
                        >
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Voltar
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Cautela ${custodyLog.cautela_number}`} />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                    {/* Informações principais da cautela */}
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        {/* Header Info */}
                        <div className="p-6 border-b grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wider">Nº da Cautela</h3>
                                <p className="text-lg font-semibold text-gray-900 font-mono">{custodyLog.cautela_number}</p>
                            </div>
                            <div>
                                <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</h3>
                                <span className={`px-2 py-1 text-sm font-medium rounded-full ${status === 'Ativa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`}>
                                    {status}
                                </span>
                            </div>
                            <div>
                                <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Abertura</h3>
                                <p className="text-gray-900">{new Date(custodyLog.checkout_date).toLocaleDateString('pt-BR')}</p>
                            </div>
                            {custodyLog.checkin_date && (
                                <div>
                                    <h3 className="text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Devolução</h3>
                                    <p className="text-gray-900">{new Date(custodyLog.checkin_date).toLocaleDateString('pt-BR')}</p>
                                </div>
                            )}
                        </div>

                        {/* User Info */}
                        <div className="p-6 border-b">
                            <h3 className="text-lg font-medium text-gray-900 mb-2">Militar Responsável</h3>
                            {custodyLog.user ? (
                                <p className="text-gray-700">{custodyLog.user.rank} {custodyLog.user.name} - ID: {custodyLog.user.military_id}</p>
                            ) : (
                                <p className="text-gray-500">Usuário não encontrado.</p>
                            )}
                        </div>

                        {/* Assets List */}
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Ativos Cautelados ({custodyLog.assets?.length || 0})</h3>
                            <div className="border rounded-lg overflow-hidden">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">QR Code</th>
                                            <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nome do Ativo</th>
                                            <th className="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nº de Série</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {custodyLog.assets?.map(asset => (
                                            <tr key={asset.id}>
                                                <td className="px-4 py-3 whitespace-nowrap font-mono text-sm">{asset.qr_code}</td>
                                                <td className="px-4 py-3 whitespace-nowrap text-sm">{asset.name}</td>
                                                <td className="px-4 py-3 whitespace-nowrap text-sm">{asset.serial_number || 'N/A'}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Actions */}
                        {!custodyLog.checkin_date && (
                            <div className="p-6 bg-gray-50 text-right">
                                <button onClick={handleDischarge} className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                    Dar Baixa (Check-Out)
                                </button>
                            </div>
                        )}
                    </div>

                    {/* Componente de Documento Assinado */}
                    <SignedDocumentViewer
                        custodyLog={custodyLog}
                        onDocumentUploaded={handleDocumentUploaded}
                        onDocumentRemoved={handleDocumentRemoved}
                    />
                </div>
            </div>
            
            <ConfirmationModal 
                isOpen={showDischargeModal} 
                onClose={() => setShowDischargeModal(false)} 
                onConfirm={handleConfirmDischarge} 
                title="Dar Baixa na Cautela"
                message={`Tem certeza que deseja dar baixa na cautela ${custodyLog.cautela_number}? Os ativos serão retornados ao almoxarifado.`}
                confirmText="Dar Baixa (Check-Out)"
                type="warning"
                requireJustification={true}
                justificationLabel="Motivo da baixa"
                justificationPlaceholder="Ex: Devolução programada, transferência, fim de uso, etc."
                icon={
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                    </svg>
                }
            />
        </SGAITILayout>
    );
}
