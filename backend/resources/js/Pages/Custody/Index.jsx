import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import ConfirmationModal from '@/Components/ConfirmationModal';

export default function Index({ custodyLogs, users }) {
    const { auth } = usePage().props;
    const user = auth.user;

    const [searchTerm, setSearchTerm] = useState('');
    const [modalState, setModalState] = useState({ isOpen: false, type: null, log: null });

    const openModal = (type, log) => {
        setModalState({ isOpen: true, type, log });
    };

    const closeModal = () => {
        setModalState({ isOpen: false, type: null, log: null });
    };

    const handleConfirm = (justification) => {
        if (!modalState.log) return;

        if (modalState.type === 'discharge') {
            router.put(route('custody.checkin', modalState.log.id), {
                checkinDate: new Date().toISOString().split('T')[0],
                justification: justification
            }, {
                preserveScroll: true,
            });
        }
        closeModal();
    };

    const handleDischarge = (log) => {
        openModal('discharge', log);
    };

    const filteredLogs = useMemo(() => {
        const sortedLogs = [...custodyLogs].sort((a, b) => new Date(b.checkout_date).getTime() - new Date(a.checkout_date).getTime());
        if (!searchTerm.trim()) {
            return sortedLogs;
        }
        const term = searchTerm.toLowerCase();
        return sortedLogs.filter(log => {
            const user = log.user;
            if (!user) return false;
            return (
                user.name.toLowerCase().includes(term) ||
                user.rank.toLowerCase().includes(term) ||
                log.cautela_number.toLowerCase().includes(term)
            );
        });
    }, [custodyLogs, searchTerm]);


    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Gestão de Cautelas
                    </h2>
                    {user.user_role === 'admin' && (
                        <Link
                            href={route('custody.create')}
                            className="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            Criar Cautela
                        </Link>
                    )}
                </div>
            }
        >
            <Head title="Cautelas - SGAITI" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="mb-4">
                        <input
                            type="text"
                            placeholder="Buscar por Nº da Cautela, nome ou posto/graduação do militar..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-left text-gray-500">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3">Nº Cautela</th>
                                        <th scope="col" className="px-6 py-3">Responsável</th>
                                        <th scope="col" className="px-6 py-3">Data Abertura</th>
                                        <th scope="col" className="px-6 py-3">Status</th>
                                        <th scope="col" className="px-6 py-3">Nº Itens</th>
                                        <th scope="col" className="px-6 py-3 text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {filteredLogs.map(log => {
                                        const status = log.checkin_date ? 'Concluída' : 'Ativa';
                                        return (
                                            <tr key={log.id} className="bg-white border-b hover:bg-gray-50">
                                                <td className="px-6 py-4 font-mono">{log.cautela_number}</td>
                                                <td className="px-6 py-4 font-medium text-gray-900">{log.user ? `${log.user.rank} ${log.user.name}` : 'N/A'}</td>
                                                <td className="px-6 py-4">{new Date(log.checkout_date).toLocaleDateString('pt-BR')}</td>
                                                <td className="px-6 py-4">
                                                    <span className={`px-2 py-1 text-xs font-medium rounded-full ${status === 'Ativa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`}>
                                                        {status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 text-center">{log.assets?.length || 0}</td>
                                                <td className="px-6 py-4 text-right">
                                                    <div className="flex items-center justify-end space-x-1">
                                                        <Link href={route('custody.show', log.id)} className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none" title="Ver Detalhes">
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        </Link>
                                                        {!log.checkin_date && (
                                                            <button onClick={() => handleDischarge(log)} className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-green-600 focus:outline-none" title="Dar Baixa na Cautela">
                                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                            </button>
                                                        )}
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })}
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
                title="Dar Baixa na Cautela"
                message={`Tem certeza que deseja dar baixa na cautela ${modalState.log?.cautela_number}? Os ativos serão retornados ao almoxarifado.`}
                confirmText="Dar Baixa"
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