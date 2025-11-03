import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ asset, sectors }) {
    const { data, setData, put, processing, errors } = useForm({
        name: asset.name || '',
        brand: asset.brand || '',
        model: asset.model || '',
        serial_number: asset.serial_number || '',
        patrimony_number: asset.patrimony_number || '',
        type: asset.type || '',
        condition: asset.condition || '',
        sector_id: asset.sector_id || '',
        purchase_value: asset.purchase_value || '',
        notes: asset.notes || '',
    });

    const assetTypes = [
        { value: 'COMPUTADOR', label: 'Computador', icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
        { value: 'NOTEBOOK', label: 'Notebook', icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z' },
        { value: 'MONITOR', label: 'Monitor', icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
        { value: 'IMPRESSORA', label: 'Impressora', icon: 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z' },
        { value: 'TELEFONE', label: 'Telefone', icon: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' },
        { value: 'OUTROS', label: 'Outros', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
    ];

    const conditions = [
        { value: 'NOVO', label: 'Novo', color: 'text-green-600', bgColor: 'bg-green-100', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        { value: 'BOM', label: 'Bom', color: 'text-blue-600', bgColor: 'bg-blue-100', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        { value: 'REGULAR', label: 'Regular', color: 'text-yellow-600', bgColor: 'bg-yellow-100', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' },
        { value: 'RUIM', label: 'Ruim', color: 'text-orange-600', bgColor: 'bg-orange-100', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' },
        { value: 'DEFEITUOSO', label: 'Defeituoso', color: 'text-red-600', bgColor: 'bg-red-50', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' },
    ];

    const submit = (e) => {
        e.preventDefault();
        put(route('assets.update', asset.id));
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <div className="flex items-center space-x-3">
                        <div className="flex-shrink-0">
                            <svg className="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h2 className="text-xl font-semibold leading-tight text-gray-800">
                                Editar Ativo
                            </h2>
                            <p className="text-sm text-gray-600">
                                {asset.name}
                            </p>
                        </div>
                    </div>
                    <div className="flex space-x-3">
                        <a
                            href={route('assets.show', asset.id)}
                            className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg className="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Visualizar
                        </a>
                        <a
                            href={route('assets.index')}
                            className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg className="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Voltar
                        </a>
                    </div>
                </div>
            }
        >
            <Head title={`Editar ${asset.name} - SGAITI`} />

            <div className="py-6">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-6">
                        {/* Informações Básicas */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center space-x-3 mb-4">
                                    <svg className="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 className="text-lg leading-6 font-medium text-gray-900">
                                        Informações Básicas
                                    </h3>
                                </div>
                                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div className="sm:col-span-2">
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
                                        <label className="block text-sm font-medium text-gray-700 mb-3">
                                            Tipo *
                                        </label>
                                        <div className="grid grid-cols-2 gap-3">
                                            {assetTypes.map((type) => (
                                                <div key={type.value} className="relative">
                                                    <input
                                                        id={type.value}
                                                        name="type"
                                                        type="radio"
                                                        value={type.value}
                                                        checked={data.type === type.value}
                                                        onChange={(e) => setData('type', e.target.value)}
                                                        className="sr-only"
                                                    />
                                                    <label
                                                        htmlFor={type.value}
                                                        className={`relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:border-blue-400 focus:outline-none ${
                                                            data.type === type.value
                                                                ? 'border-blue-500 bg-blue-50'
                                                                : 'border-gray-300'
                                                        }`}
                                                    >
                                                        <svg className={`w-8 h-8 mb-2 ${
                                                            data.type === type.value ? 'text-blue-600' : 'text-gray-400'
                                                        }`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={type.icon} />
                                                        </svg>
                                                        <span className={`text-sm font-medium ${
                                                            data.type === type.value ? 'text-blue-900' : 'text-gray-900'
                                                        }`}>
                                                            {type.label}
                                                        </span>
                                                    </label>
                                                </div>
                                            ))}
                                        </div>
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
                                        <label className="block text-sm font-medium text-gray-700 mb-3">
                                            Estado de Conservação *
                                        </label>
                                        <div className="space-y-2">
                                            {conditions.map((condition) => (
                                                <div key={condition.value} className="flex items-center">
                                                    <input
                                                        id={condition.value}
                                                        name="condition"
                                                        type="radio"
                                                        value={condition.value}
                                                        checked={data.condition === condition.value}
                                                        onChange={(e) => setData('condition', e.target.value)}
                                                        className="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                                    />
                                                    <label htmlFor={condition.value} className="ml-3 flex items-center">
                                                        <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${condition.bgColor} ${condition.color} mr-2`}>
                                                            <svg className="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={condition.icon} />
                                                            </svg>
                                                            {condition.label}
                                                        </span>
                                                    </label>
                                                </div>
                                            ))}
                                        </div>
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
                                    className="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 mb-4"
                                >
                                    <svg
                                        className={`mr-2 h-5 w-5 transform transition-transform ${showAdvanced ? 'rotate-90' : ''}`}
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                    <svg className="mr-2 h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Informações Avançadas
                                </button>

                                {showAdvanced && (
                                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                                <div className="flex items-center space-x-3 mb-4">
                                    <svg className="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <h3 className="text-lg leading-6 font-medium text-gray-900">
                                        Observações
                                    </h3>
                                </div>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows={4}
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    className="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Informações adicionais sobre o ativo..."
                                />
                                {errors.notes && <p className="mt-1 text-sm text-red-600">{errors.notes}</p>}
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex justify-end space-x-3">
                            <a
                                href={route('assets.show', asset.id)}
                                className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg className="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            >
                                {processing ? (
                                    <>
                                        <svg className="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Salvando...
                                    </>
                                ) : (
                                    <>
                                        <svg className="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                        </svg>
                                        Salvar Alterações
                                    </>
                                )}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SGAITILayout>
    );
}
