import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

const ConfirmationModal = ({ isOpen, onClose, onConfirm, title, message, justificationLabel, justificationPlaceholder, confirmButtonText, confirmButtonClass }) => {
    const [justification, setJustification] = useState('');

    const handleSubmit = () => {
        if (justification.trim()) {
            onConfirm(justification);
            setJustification('');
        } else {
            alert('Por favor, insira uma justificativa.');
        }
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div className="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 className="text-lg font-bold mb-4">{title}</h3>
                <p className="mb-4">{message}</p>
                <textarea
                    value={justification}
                    onChange={(e) => setJustification(e.target.value)}
                    placeholder={justificationPlaceholder}
                    rows={3}
                    className="w-full p-2 border border-gray-300 rounded-md mb-4"
                ></textarea>
                <div className="flex justify-end space-x-2">
                    <button onClick={onClose} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button onClick={handleSubmit} className={`px-4 py-2 text-white rounded-md ${confirmButtonClass}`}>{confirmButtonText}</button>
                </div>
            </div>
        </div>
    );
};

export default function Index({ inventoryRecords }) {
    const [modalState, setModalState] = useState({ isOpen: false, type: null, inventory: null });

    const getStatusBadge = (status) => {
        switch(status) {
            case 'Concluído': return 'bg-green-100 text-green-800';
            case 'Reaberto': return 'bg-yellow-100 text-yellow-800';
            case 'Em Andamento': return 'bg-blue-100 text-blue-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    const openModal = (type, inventory) => {
        setModalState({ isOpen: true, type, inventory });
    };

    const closeModal = () => {
        setModalState({ isOpen: false, type: null, inventory: null });
    };

    const handleConfirm = (justification) => {
        if (!modalState.inventory) return;

        if (modalState.type === 'delete') {
            router.delete(route('inventory.destroy', modalState.inventory.id), { data: { justification } });
        } else if (modalState.type === 'reopen') {
            router.put(route('inventory.reopen', modalState.inventory.id), { justification });
        }
        closeModal();
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Gestão de Inventário
                    </h2>
                    <Link
                        href={route('inventory.create')}
                        className="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Iniciar Novo Inventário
                    </Link>
                </div>
            }
        >
            <Head title="Inventário - SGAITI" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-left text-gray-500">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3">Nº Comissão</th>
                                        <th scope="col" className="px-6 py-3">Setor</th>
                                        <th scope="col" className="px-6 py-3">Responsável</th>
                                        <th scope="col" className="px-6 py-3">Data Início</th>
                                        <th scope="col" className="px-6 py-3">Status</th>
                                        <th scope="col" className="px-6 py-3 text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {inventoryRecords.map(record => (
                                        <tr key={record.id} className="bg-white border-b hover:bg-gray-50">
                                            <td className="px-6 py-4 font-mono">{record.commission_number}</td>
                                            <td className="px-6 py-4 font-medium text-gray-900">{record.sector ? record.sector.name : 'Global'}</td>
                                            <td className="px-6 py-4">{record.responsible_user ? `${record.responsible_user.rank} ${record.responsible_user.name}` : 'N/A'}</td>
                                            <td className="px-6 py-4">{new Date(record.start_date).toLocaleDateString('pt-BR')}</td>
                                            <td className="px-6 py-4">
                                                <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge(record.status)}`}>
                                                    {record.status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 text-right">
                                                <div className="flex items-center justify-end space-x-2">
                                                    <Link 
                                                        href={route('inventory.show', record.id)} 
                                                        className="text-gray-400 hover:text-blue-600 p-1 rounded-full hover:bg-gray-100"
                                                        title={record.status === 'Em Andamento' || record.status === 'Reaberto' ? 'Continuar Inventário' : 'Ver Detalhes'}
                                                    >
                                                        {record.status === 'Em Andamento' || record.status === 'Reaberto' ? (
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        ) : (
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        )}
                                                    </Link>
                                                    {record.status === 'Concluído' && (
                                                        <button 
                                                            onClick={() => openModal('reopen', record)} 
                                                            className="text-gray-400 hover:text-yellow-600 p-1 rounded-full hover:bg-gray-100"
                                                            title="Reabrir Inventário"
                                                        >
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                                                        </button>
                                                    )}
                                                    <button 
                                                        onClick={() => openModal('delete', record)} 
                                                        className="text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-gray-100"
                                                        title="Excluir Inventário"
                                                    >
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <ConfirmationModal 
                isOpen={modalState.isOpen} 
                onClose={closeModal} 
                onConfirm={handleConfirm} 
                title={modalState.type === 'delete' ? 'Confirmar Exclusão' : 'Confirmar Reabertura'}
                message={modalState.type === 'delete' ? 'Tem certeza que deseja excluir este registro?' : 'Tem certeza que deseja reabrir este inventário?'}
                justificationPlaceholder={modalState.type === 'delete' ? 'Justificativa para a exclusão...' : 'Justificativa para a reabertura...'}
                confirmButtonText={modalState.type === 'delete' ? 'Excluir' : 'Reabrir'}
                confirmButtonClass={modalState.type === 'delete' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700'}
            />
        </SGAITILayout>
    );
}