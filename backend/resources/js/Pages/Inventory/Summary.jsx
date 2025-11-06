import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import ConfirmationModal from '@/Components/ConfirmationModal';

const SummarySection = ({ title, items = [], color = "green" }) => (
    <div className="bg-white shadow-sm rounded-lg p-4">
        <h3 className={`font-bold text-lg mb-2 text-${color}-600`}>{title} ({items.length})</h3>
        <div className="h-64 overflow-y-auto">
            <ul className="divide-y divide-gray-200">
                {items.map((item, index) => (
                    <li key={index} className="py-2 px-1">
                        <div>
                            <p className="font-semibold">{item.name || item.description}</p>
                            {item.qr_code && (
                                <p className="text-sm text-gray-500 font-mono">{item.qr_code}</p>
                            )}
                            {item.serial_number && (
                                <p className="text-sm text-gray-500">SN: {item.serial_number}</p>
                            )}
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    </div>
);

export default function Summary({ inventory, foundAssets = [], pendingAssets = [], uncataloguedItems = [] }) {
    const [showReopenModal, setShowReopenModal] = useState(false);

    const handleReopenClick = () => {
        setShowReopenModal(true);
    };

    const handleConfirmReopen = (justification) => {
        router.put(route('inventory.reopen', inventory.id), {
            justification: justification
        });
    };
    const totalAssets = foundAssets.length + pendingAssets.length;
    const foundPercentage = totalAssets > 0 ? ((foundAssets.length / totalAssets) * 100).toFixed(1) : 0;

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Resumo do Inventário: {inventory.commission_number || 'Sem Comissão'}
                    </h2>
                    <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {inventory.status}
                    </span>
                </div>
            }
        >
            <Head title={`Resumo - Inventário ${inventory.commission_number || 'Sem Comissão'}`} />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {/* Informações Gerais */}
                    <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h3 className="text-lg font-semibold mb-4">Informações Gerais</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Setor</label>
                                <p className="text-lg">{inventory.sector?.name}</p>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Responsável</label>
                                <p className="text-lg">{inventory.responsible_user?.name}</p>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Data de Início</label>
                                <p className="text-lg">{new Date(inventory.start_date).toLocaleDateString('pt-BR')}</p>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Data de Conclusão</label>
                                <p className="text-lg">
                                    {inventory.end_date ? new Date(inventory.end_date).toLocaleDateString('pt-BR') : 'N/A'}
                                </p>
                            </div>
                        </div>
                        
                        {inventory.commission_number && (
                            <div className="mt-4">
                                <label className="block text-sm font-medium text-gray-700">Número da Comissão</label>
                                <p className="text-lg">{inventory.commission_number}</p>
                            </div>
                        )}
                    </div>

                    {/* Estatísticas */}
                    <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h3 className="text-lg font-semibold mb-4">Estatísticas do Inventário</h3>
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div className="bg-green-50 p-4 rounded-lg">
                                <div className="text-2xl font-bold text-green-600">{foundAssets.length}</div>
                                <div className="text-sm text-green-700">Itens Encontrados</div>
                            </div>
                            <div className="bg-red-50 p-4 rounded-lg">
                                <div className="text-2xl font-bold text-red-600">{pendingAssets.length}</div>
                                <div className="text-sm text-red-700">Itens Pendentes</div>
                            </div>
                            <div className="bg-blue-50 p-4 rounded-lg">
                                <div className="text-2xl font-bold text-blue-600">{uncataloguedItems.length}</div>
                                <div className="text-sm text-blue-700">Itens Não Catalogados</div>
                            </div>
                            <div className="bg-purple-50 p-4 rounded-lg">
                                <div className="text-2xl font-bold text-purple-600">{foundPercentage}%</div>
                                <div className="text-sm text-purple-700">Taxa de Localização</div>
                            </div>
                        </div>
                    </div>

                    {/* Progress Bar */}
                    <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h3 className="text-lg font-semibold mb-4">Progresso do Inventário</h3>
                        <div className="w-full bg-gray-200 rounded-full h-4">
                            <div 
                                className="bg-green-600 h-4 rounded-full transition-all duration-300" 
                                style={{ width: `${foundPercentage}%` }}
                            ></div>
                        </div>
                        <div className="flex justify-between text-sm text-gray-600 mt-2">
                            <span>0%</span>
                            <span className="font-medium">{foundPercentage}% Concluído</span>
                            <span>100%</span>
                        </div>
                    </div>

                    {/* Detalhes dos Itens */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <SummarySection 
                            title="Itens Encontrados" 
                            items={foundAssets} 
                            color="green" 
                        />
                        <SummarySection 
                            title="Itens Pendentes" 
                            items={pendingAssets} 
                            color="red" 
                        />
                        <div className="bg-white shadow-sm rounded-lg p-4">
                            <h3 className="font-bold text-lg mb-2 text-blue-600">
                                Itens Não Catalogados ({uncataloguedItems.length})
                            </h3>
                            <div className="h-64 overflow-y-auto">
                                <ul className="divide-y divide-gray-200">
                                    {uncataloguedItems.map((item, index) => (
                                        <li key={index} className="py-2 px-1">
                                            <p>{typeof item === 'string' ? item : item.description}</p>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>

                    {/* Observações */}
                    {inventory.notes && (
                        <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                            <h3 className="text-lg font-semibold mb-4">Observações Gerais</h3>
                            <div className="bg-gray-50 p-4 rounded-lg">
                                <p className="whitespace-pre-wrap">{inventory.notes}</p>
                            </div>
                        </div>
                    )}

                    {/* Ações */}
                    <div className="flex justify-between items-center">
                        <Link 
                            href={route('inventory.index')} 
                            className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition"
                        >
                            Voltar à Lista
                        </Link>
                        
                        <div className="flex space-x-4">
                            <a
                                href={route('inventory.printReport', { inventory: inventory.id })}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition inline-flex items-center"
                            >
                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Gerar Relatório PDF
                            </a>
                            
                            {inventory.status === 'Concluído' && (
                                <button
                                    onClick={handleReopenClick}
                                    className="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition inline-flex items-center"
                                >
                                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reabrir para Edição
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            </div>
            
            <ConfirmationModal 
                isOpen={showReopenModal} 
                onClose={() => setShowReopenModal(false)} 
                onConfirm={handleConfirmReopen} 
                title="Reabrir Inventário"
                message={`Tem certeza que deseja reabrir o inventário "${inventory.commission_number}" para edição? Você poderá fazer alterações novamente.`}
                confirmText="Reabrir"
                type="warning"
                requireJustification={true}
                justificationLabel="Justificativa para reabertura"
                justificationPlaceholder="Ex: Contagem inicial incompleta, necessidade de incluir novos itens, correções necessárias, etc."
                icon={
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5" />
                    </svg>
                }
            />
        </SGAITILayout>
    );
}