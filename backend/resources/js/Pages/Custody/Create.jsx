import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState, useMemo } from 'react';

// Este componente é o formulário para adicionar ativos à cautela
const AssetSelector = ({ availableAssets, selectedAssetIds, onAdd, onRemove, error }) => {
    const [assetQrCode, setAssetQrCode] = useState('');

    const handleAddAsset = () => {
        const qrCode = assetQrCode.trim().toLowerCase();
        if (!qrCode) return;

        const asset = availableAssets.find(a => a.qr_code.toLowerCase() === qrCode);
        if (asset) {
            if (!selectedAssetIds.includes(asset.id)) {
                onAdd(asset.id);
                setAssetQrCode('');
            }
        } else {
            onRemove(null, 'Ativo não encontrado ou não disponível.');
        }
    };

    const selectedAssets = availableAssets.filter(a => selectedAssetIds.includes(a.id));

    return (
        <div className="p-4 border rounded-md bg-gray-50">
            <label className="block text-sm font-medium text-gray-700">Adicionar Ativo por QR Code</label>
            <div className="flex items-center mt-1">
                <input 
                    type="text" 
                    value={assetQrCode} 
                    onChange={(e) => setAssetQrCode(e.target.value)} 
                    onKeyPress={(e) => e.key === 'Enter' && handleAddAsset()} 
                    placeholder="Digite ou escaneie o QR Code"
                    className="flex-grow block w-full border-gray-300 rounded-l-md shadow-sm"
                />
                <button type="button" onClick={handleAddAsset} className="px-4 py-2 bg-gray-600 text-white rounded-r-md hover:bg-gray-700">Adicionar</button>
            </div>
            {error && <p className="text-red-500 text-sm mt-1">{error}</p>}

            <div className="mt-4 h-48 overflow-y-auto border rounded-md p-2 bg-white">
                <h3 className="font-semibold mb-2 text-gray-700">Ativos na Cautela: ({selectedAssets.length})</h3>
                {selectedAssets.length === 0 ? (
                    <p className="text-gray-500 text-sm">Nenhum ativo adicionado.</p>
                ) : (
                    <ul>
                        {selectedAssets.map(asset => (
                            <li key={asset.id} className="flex justify-between items-center p-2 bg-white rounded shadow-sm mb-1">
                                <span>{asset.qr_code} - {asset.name}</span>
                                <button type="button" onClick={() => onRemove(asset.id)} className="text-red-500 hover:text-red-700 p-1 rounded-full" title="Remover">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </div>
    );
};

export default function Create({ users, assets, nextCautelaNumber }) {
    const { data, setData, post, processing, errors } = useForm({
        cautelaNumber: nextCautelaNumber,
        userId: '',
        checkoutDate: new Date().toISOString().split('T')[0],
        assetIds: [],
        notes: ''
    });

    const availableAssets = useMemo(() => assets.filter(a => a.status === 'Disponível'), [assets]);

    const handleAddAssetId = (assetId) => {
        setData('assetIds', [...data.assetIds, assetId]);
    };

    const handleRemoveAssetId = (assetId, customError = null) => {
        if (assetId) {
            setData('assetIds', data.assetIds.filter(id => id !== assetId));
        }
        if (customError) {
            errors.assetIds = customError;
        }
    };

    function submit(e) {
        e.preventDefault();
        post(route('custody.store'));
    }

    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Criar Nova Cautela
                </h2>
            }
        >
            <Head title="Nova Cautela - SGAITI" />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-md rounded-lg p-8">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="cautelaNumber" className="block text-sm font-medium text-gray-700">Nº da Cautela</label>
                                    <input 
                                        type="text" 
                                        id="cautelaNumber" 
                                        value={data.cautelaNumber} 
                                        onChange={e => setData('cautelaNumber', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                                        required
                                        readOnly
                                    />
                                    {errors.cautelaNumber && <p className="text-red-500 text-xs mt-1">{errors.cautelaNumber}</p>}
                                </div>
                                <div>
                                    <label htmlFor="checkoutDate" className="block text-sm font-medium text-gray-700">Data de Abertura</label>
                                    <input 
                                        type="date" 
                                        id="checkoutDate" 
                                        value={data.checkoutDate} 
                                        onChange={e => setData('checkoutDate', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        required
                                    />
                                    {errors.checkoutDate && <p className="text-red-500 text-xs mt-1">{errors.checkoutDate}</p>}
                                </div>
                            </div>

                            <div className="mt-4">
                                <label htmlFor="userId" className="block text-sm font-medium text-gray-700">Militar Responsável</label>
                                <select 
                                    id="userId" 
                                    value={data.userId} 
                                    onChange={e => setData('userId', e.target.value)} 
                                    required 
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">Selecione um militar</option>
                                    {users.filter(u => u.is_active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
                                </select>
                                {errors.userId && <p className="text-red-500 text-xs mt-1">{errors.userId}</p>}
                            </div>

                            <div className="mt-6">
                                <AssetSelector 
                                    availableAssets={availableAssets}
                                    selectedAssetIds={data.assetIds}
                                    onAdd={handleAddAssetId}
                                    onRemove={handleRemoveAssetId}
                                    error={errors.assetIds}
                                />
                                {errors.assetIds && <p className="text-red-500 text-xs mt-1">{errors.assetIds}</p>}
                            </div>

                            <div className="mt-4">
                                <label htmlFor="notes" className="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea 
                                    id="notes" 
                                    value={data.notes} 
                                    onChange={e => setData('notes', e.target.value)} 
                                    rows={3}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                ></textarea>
                                {errors.notes && <p className="text-red-500 text-xs mt-1">{errors.notes}</p>}
                            </div>

                            <div className="mt-8 flex justify-end space-x-4">
                                <Link href={route('custody.index')} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</Link>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50">
                                    {processing ? 'Salvando...' : 'Salvar Cautela'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
