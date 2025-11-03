import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Create({ sectors }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        brand: '',
        model: '',
        serial_number: '',
        patrimony_number: '',
        type: '',
        condition: '',
        sector_id: '',
        purchase_value: '',
        notes: '',
    });

    const [showAdvanced, setShowAdvanced] = useState(false);

    const assetTypes = [
        { value: 'COMPUTADOR', label: 'Computador' },
        { value: 'NOTEBOOK', label: 'Notebook' },
        { value: 'MONITOR', label: 'Monitor' },
        { value: 'IMPRESSORA', label: 'Impressora' },
        { value: 'TELEFONE', label: 'Telefone' },
        { value: 'OUTROS', label: 'Outros' },
    ];

    const conditions = [
        { value: 'NOVO', label: 'Novo', color: 'text-green-600' },
        { value: 'BOM', label: 'Bom', color: 'text-blue-600' },
        { value: 'REGULAR', label: 'Regular', color: 'text-yellow-600' },
        { value: 'RUIM', label: 'Ruim', color: 'text-orange-600' },
        { value: 'DEFEITUOSO', label: 'Defeituoso', color: 'text-red-600' },
    ];

    const submit = (e) => {
        e.preventDefault();
        post(route('assets.store'));
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Novo Ativo
                    </h2>
                    <a
                        href={route('assets.index')}
                        className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                    >
                        Voltar
                    </a>
                </div>
            }
        >
            <Head title="Novo Ativo - SGAITI" />

            <div className="py-6">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-6">
                        {/* Informações Básicas */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Informações Básicas
                                </h3>
                                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                            Nome do Ativo *
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: Notebook Dell Latitude"
                                            required
                                        />
                                        {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="type" className="block text-sm font-medium text-gray-700">
                                            Tipo *
                                        </label>
                                        <select
                                            name="type"
                                            id="type"
                                            value={data.type}
                                            onChange={(e) => setData('type', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Selecione um tipo</option>
                                            {assetTypes.map((type) => (
                                                <option key={type.value} value={type.value}>
                                                    {type.label}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.type && <p className="mt-1 text-sm text-red-600">{errors.type}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="brand" className="block text-sm font-medium text-gray-700">
                                            Marca *
                                        </label>
                                        <input
                                            type="text"
                                            name="brand"
                                            id="brand"
                                            value={data.brand}
                                            onChange={(e) => setData('brand', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: Dell"
                                            required
                                        />
                                        {errors.brand && <p className="mt-1 text-sm text-red-600">{errors.brand}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="model" className="block text-sm font-medium text-gray-700">
                                            Modelo
                                        </label>
                                        <input
                                            type="text"
                                            name="model"
                                            id="model"
                                            value={data.model}
                                            onChange={(e) => setData('model', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: Latitude 5420"
                                        />
                                        {errors.model && <p className="mt-1 text-sm text-red-600">{errors.model}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="condition" className="block text-sm font-medium text-gray-700">
                                            Estado de Conservação *
                                        </label>
                                        <select
                                            name="condition"
                                            id="condition"
                                            value={data.condition}
                                            onChange={(e) => setData('condition', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Selecione o estado</option>
                                            {conditions.map((condition) => (
                                                <option key={condition.value} value={condition.value}>
                                                    {condition.label}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.condition && <p className="mt-1 text-sm text-red-600">{errors.condition}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="sector_id" className="block text-sm font-medium text-gray-700">
                                            Setor *
                                        </label>
                                        <select
                                            name="sector_id"
                                            id="sector_id"
                                            value={data.sector_id}
                                            onChange={(e) => setData('sector_id', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Selecione um setor</option>
                                            {sectors.map((sector) => (
                                                <option key={sector.id} value={sector.id}>
                                                    {sector.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.sector_id && <p className="mt-1 text-sm text-red-600">{errors.sector_id}</p>}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Informações Avançadas */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <button
                                    type="button"
                                    onClick={() => setShowAdvanced(!showAdvanced)}
                                    className="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
                                >
                                    <svg
                                        className={`mr-2 h-5 w-5 transform ${showAdvanced ? 'rotate-90' : ''}`}
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                    Informações Avançadas
                                </button>

                                {showAdvanced && (
                                    <div className="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label htmlFor="serial_number" className="block text-sm font-medium text-gray-700">
                                                Número de Série
                                            </label>
                                            <input
                                                type="text"
                                                name="serial_number"
                                                id="serial_number"
                                                value={data.serial_number}
                                                onChange={(e) => setData('serial_number', e.target.value)}
                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="Ex: ABC123456"
                                            />
                                            {errors.serial_number && <p className="mt-1 text-sm text-red-600">{errors.serial_number}</p>}
                                        </div>

                                        <div>
                                            <label htmlFor="patrimony_number" className="block text-sm font-medium text-gray-700">
                                                Número do Patrimônio
                                            </label>
                                            <input
                                                type="text"
                                                name="patrimony_number"
                                                id="patrimony_number"
                                                value={data.patrimony_number}
                                                onChange={(e) => setData('patrimony_number', e.target.value)}
                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="Ex: PAT-001"
                                            />
                                            {errors.patrimony_number && <p className="mt-1 text-sm text-red-600">{errors.patrimony_number}</p>}
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label htmlFor="purchase_value" className="block text-sm font-medium text-gray-700">
                                                Valor de Compra (R$)
                                            </label>
                                            <input
                                                type="number"
                                                name="purchase_value"
                                                id="purchase_value"
                                                value={data.purchase_value}
                                                onChange={(e) => setData('purchase_value', e.target.value)}
                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="0,00"
                                                step="0.01"
                                                min="0"
                                            />
                                            {errors.purchase_value && <p className="mt-1 text-sm text-red-600">{errors.purchase_value}</p>}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Observações */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <label htmlFor="notes" className="block text-sm font-medium text-gray-700">
                                    Observações
                                </label>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows={4}
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Informações adicionais sobre o ativo..."
                                />
                                {errors.notes && <p className="mt-1 text-sm text-red-600">{errors.notes}</p>}
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex justify-end space-x-3">
                            <a
                                href={route('assets.index')}
                                className="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                            >
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50"
                            >
                                {processing ? 'Salvando...' : 'Salvar Ativo'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SGAITILayout>
    );
}
