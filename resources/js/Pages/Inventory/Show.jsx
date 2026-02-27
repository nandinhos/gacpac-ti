import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import ConfirmationModal from '@/Components/ConfirmationModal';

const AssetList = ({ title, assets = [], onSelectAll, onSelect, selection, showCheckboxes }) => (
    <div className="bg-white shadow-sm rounded-lg p-4">
        <h3 className={`font-bold text-lg mb-2 ${title === 'Pendentes' ? 'text-red-600' : 'text-green-600'}`}>{title} ({assets.length})</h3>
        {showCheckboxes && (
            <div className="pb-2 mb-2 border-b">
                <input type="checkbox" onChange={onSelectAll} /> Selecionar Todos
            </div>
        )}
        <div className="h-96 overflow-y-auto">
            <ul className="divide-y divide-gray-200">
                {assets.map(asset => (
                    <li key={asset.id} className="py-2 px-1 flex justify-between items-center">
                        <div className="flex items-center">
                            {showCheckboxes && <input type="checkbox" checked={selection.includes(asset.id)} onChange={() => onSelect(asset.id)} className="mr-2" />}
                            <div>
                                <p className="font-semibold">{asset.name}</p>
                                <p className="text-sm text-gray-500 font-mono">{asset.qr_code || asset.serial_number}</p>
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    </div>
);

const UncataloguedList = ({ items = [], onAddItem, onRemoveItem, onEditItem }) => {
    const [newItem, setNewItem] = useState('');
    const [editingItem, setEditingItem] = useState(null);
    const [editText, setEditText] = useState('');
    const [showDeleteModal, setShowDeleteModal] = useState(null);

    const handleAddItem = () => {
        if (newItem.trim()) {
            onAddItem(newItem.trim());
            setNewItem('');
        }
    };

    const handleEditStart = (item) => {
        setEditingItem(item.id);
        setEditText(item.description);
    };

    const handleEditSave = (item) => {
        if (editText.trim() && editText !== item.description) {
            onEditItem(item.id, editText.trim());
        }
        setEditingItem(null);
        setEditText('');
    };

    const handleEditCancel = () => {
        setEditingItem(null);
        setEditText('');
    };

    const handleDeleteClick = (item) => {
        setShowDeleteModal(item);
    };

    const handleDeleteConfirm = () => {
        onRemoveItem(showDeleteModal.id);
        setShowDeleteModal(null);
    };

    return (
        <div className="bg-white shadow-sm rounded-lg p-4">
            <h3 className="font-bold text-lg mb-2">Itens não Catalogados ({items.length})</h3>
            <div className="h-96 overflow-y-auto">
                <ul className="divide-y divide-gray-200">
                    {items.map((item, index) => (
                        <li key={index} className="py-2 px-1 flex justify-between items-center">
                            <div className="flex-grow">
                                {editingItem === item.id ? (
                                    <input
                                        type="text"
                                        value={editText}
                                        onChange={(e) => setEditText(e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm"
                                        onKeyPress={(e) => e.key === 'Enter' && handleEditSave(item)}
                                        autoFocus
                                    />
                                ) : (
                                    <p>{typeof item === 'string' ? item : item.description}</p>
                                )}
                            </div>
                            <div className="flex items-center space-x-2 ml-2">
                                {editingItem === item.id ? (
                                    <>
                                        <button 
                                            onClick={() => handleEditSave(item)}
                                            className="text-green-600 hover:text-green-800"
                                            title="Salvar"
                                        >
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button 
                                            onClick={handleEditCancel}
                                            className="text-gray-600 hover:text-gray-800"
                                            title="Cancelar"
                                        >
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </>
                                ) : (
                                    <>
                                        <button 
                                            onClick={() => handleEditStart(item)}
                                            className="text-blue-600 hover:text-blue-800"
                                            title="Editar"
                                        >
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            onClick={() => handleDeleteClick(item)}
                                            className="text-red-600 hover:text-red-800"
                                            title="Excluir"
                                        >
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </>
                                )}
                            </div>
                        </li>
                    ))}
                </ul>

                {/* Modal de Confirmação de Exclusão */}
                {showDeleteModal && (
                    <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div className="mt-3 text-center">
                                <h3 className="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                                <div className="mt-2 px-7 py-3">
                                    <p className="text-sm text-gray-500">
                                        Tem certeza que deseja excluir o item:<br />
                                        <strong>"{showDeleteModal.description}"</strong>?
                                    </p>
                                    <p className="text-xs text-red-600 mt-2">Esta ação não pode ser desfeita.</p>
                                </div>
                                <div className="flex justify-center space-x-4 pt-2">
                                    <button
                                        onClick={() => setShowDeleteModal(null)}
                                        className="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        onClick={handleDeleteConfirm}
                                        className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
            <div className="mt-2 pt-2 border-t">
                <div className="flex items-center">
                    <input 
                        type="text" 
                        value={newItem}
                        onChange={(e) => setNewItem(e.target.value)}
                        placeholder="Descrição do item..."
                        className="flex-grow border-gray-300 rounded-l-md shadow-sm"
                    />
                    <button onClick={handleAddItem} className="px-4 py-2 bg-gray-600 text-white rounded-r-md hover:bg-gray-700">Adicionar</button>
                </div>
            </div>
        </div>
    );
};

export default function Show({ inventory, pendingAssets = [], foundAssets = [], uncataloguedItems = [] }) {
    const [qrCode, setQrCode] = useState('');
    const [selectedPending, setSelectedPending] = useState([]);
    const [selectedFound, setSelectedFound] = useState([]);
    const [showFinishModal, setShowFinishModal] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        notes: inventory.notes || '',
    });

    const handleFindAsset = (e) => {
        e.preventDefault();
        if (!qrCode.trim()) return;

        router.post(route('inventory.findAsset', { inventory: inventory.id }), {
            qr_code: qrCode,
        }, {
            preserveScroll: true,
            onSuccess: () => setQrCode(''),
        });
    };

    const handleAddUncatalogued = (description) => {
        router.post(route('inventory.addUncatalogued', { inventory: inventory.id }), {
            description: description,
        }, { preserveScroll: true });
    };

    const handleRemoveUncatalogued = (itemId) => {
        router.delete(route('inventory.removeUncatalogued', { inventory: inventory.id, item: itemId }), {
            preserveScroll: true,
        });
    };

    const handleEditUncatalogued = (itemId, newDescription) => {
        router.put(route('inventory.editUncatalogued', { inventory: inventory.id, item: itemId }), {
            description: newDescription,
        }, { 
            preserveScroll: true 
        });
    };

    const handleFinishInventory = () => {
        setShowFinishModal(true);
    };

    const handleConfirmFinish = async () => {
        router.put(route('inventory.update', { inventory: inventory.id }), {
            status: 'Concluído',
            notes: data.notes,
        });
    };

    const handleSelectPending = (assetId) => {
        setSelectedPending(prev => 
            prev.includes(assetId) ? prev.filter(id => id !== assetId) : [...prev, assetId]
        );
    };

    const handleSelectAllPending = () => {
        if (selectedPending.length === pendingAssets.length) {
            setSelectedPending([]);
        } else {
            setSelectedPending(pendingAssets.map(a => a.id));
        }
    };

    const handleSelectFound = (assetId) => {
        setSelectedFound(prev => 
            prev.includes(assetId) ? prev.filter(id => id !== assetId) : [...prev, assetId]
        );
    };

    const handleSelectAllFound = () => {
        if (selectedFound.length === foundAssets.length) {
            setSelectedFound([]);
        } else {
            setSelectedFound(foundAssets.map(a => a.id));
        }
    };

    const handleBulkFind = () => {
        if (selectedPending.length === 0) return;
        router.post(route('inventory.bulkFind', { inventory: inventory.id }), {
            asset_ids: selectedPending,
        }, {
            preserveScroll: true,
            onSuccess: () => setSelectedPending([]),
        });
    };

    const handleBulkRemove = () => {
        if (selectedFound.length === 0) return;
        router.post(route('inventory.bulkRemove', { inventory: inventory.id }), {
            asset_ids: selectedFound,
        }, {
            preserveScroll: true,
            onSuccess: () => setSelectedFound([]),
        });
    };

    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Inventário: {inventory.commission_number}
                </h2>
            }
        >
            <Head title={`Inventário ${inventory.commission_number}`} />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                        <form onSubmit={handleFindAsset} className="flex items-center space-x-2">
                            <input 
                                type="text" 
                                value={qrCode}
                                onChange={e => setQrCode(e.target.value)}
                                placeholder="Escanear ou digitar QR Code do ativo..."
                                className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm"
                            />
                            <button type="submit" className="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Encontrar</button>
                        </form>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="md:col-span-1">
                            <AssetList 
                                title="Pendentes" 
                                assets={pendingAssets} 
                                onSelectAll={handleSelectAllPending} 
                                onSelect={handleSelectPending} 
                                selection={selectedPending} 
                                showCheckboxes={true} 
                            />
                            <button 
                                onClick={handleBulkFind} 
                                disabled={selectedPending.length === 0}
                                className="mt-2 w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400"
                            >
                                Marcar como Conferido ({selectedPending.length})
                            </button>
                        </div>
                        <div className="md:col-span-1">
                            <AssetList 
                                title="Conferidos" 
                                assets={foundAssets} 
                                onSelectAll={handleSelectAllFound} 
                                onSelect={handleSelectFound} 
                                selection={selectedFound} 
                                showCheckboxes={true} 
                            />
                            <button 
                                onClick={handleBulkRemove} 
                                disabled={selectedFound.length === 0}
                                className="mt-2 w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 disabled:bg-gray-400"
                            >
                                Marcar como Pendente ({selectedFound.length})
                            </button>
                        </div>
                        <div className="md:col-span-1">
                            <UncataloguedList 
                                items={uncataloguedItems} 
                                onAddItem={handleAddUncatalogued} 
                                onRemoveItem={handleRemoveUncatalogued}
                                onEditItem={handleEditUncatalogued}
                            />
                        </div>
                    </div>

                    <div className="mt-6">
                        <label htmlFor="notes" className="block text-sm font-medium text-gray-700">Observações Gerais</label>
                        <textarea 
                            id="notes" 
                            value={data.notes} 
                            onChange={e => setData('notes', e.target.value)} 
                            rows={4}
                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        ></textarea>
                    </div>

                    <div className="mt-8 flex justify-between items-center">
                        <Link href={route('inventory.index')} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Voltar</Link>
                        <button onClick={handleFinishInventory} disabled={processing} className="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50">
                            {processing ? 'Finalizando...' : 'Finalizar Inventário'}
                        </button>
                    </div>
                </div>
            </div>
            
            <ConfirmationModal 
                isOpen={showFinishModal} 
                onClose={() => setShowFinishModal(false)} 
                onConfirm={handleConfirmFinish} 
                title="Concluir Inventário"
                message="Tem certeza que deseja concluir este inventário? Esta ação marcará o inventário como finalizado."
                confirmText="Concluir"
                type="success"
                icon={
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                }
            />
        </SGAITILayout>
    );
}
