import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, useForm } from '@inertiajs/react';

export default function Create({ sectors }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        brand: '',
        type: '',
        condition: '',
        sector_id: '',
    });

    const assetTypes = [
        { value: 'COMPUTADOR', label: 'Computador' },
        { value: 'NOTEBOOK', label: 'Notebook' },
        { value: 'MONITOR', label: 'Monitor' },
        { value: 'IMPRESSORA', label: 'Impressora' },
        { value: 'TELEFONE', label: 'Telefone' },
        { value: 'OUTROS', label: 'Outros' },
    ];

    const conditions = [
        { value: 'NOVO', label: 'Novo' },
        { value: 'BOM', label: 'Bom' },
        { value: 'REGULAR', label: 'Regular' },
        { value: 'RUIM', label: 'Ruim' },
        { value: 'DEFEITUOSO', label: 'Defeituoso' },
    ];

    const submit = (e) => {
        e.preventDefault();
        post(route('assets.store'));
    };

    return (
        <SGAITILayout
            header={
                <div className="flex items-center space-x-3">
                    <svg className="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Novo Ativo
                    </h2>
                </div>
            }
        >
            <Head title="Novo Ativo - SGTI-GAC" />

            <div className="py-6">
                <div className="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-4">
                        <div className="bg-white shadow rounded-lg p-6">
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Nome
                                    </label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Marca
                                    </label>
                                    <input
                                        type="text"
                                        value={data.brand}
                                        onChange={(e) => setData('brand', e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    />
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo
                                    </label>
                                    <select
                                        value={data.type}
                                        onChange={(e) => setData('type', e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    >
                                        <option value="">Selecione</option>
                                        {assetTypes.map((type) => (
                                            <option key={type.value} value={type.value}>
                                                {type.label}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Estado
                                    </label>
                                    <select
                                        value={data.condition}
                                        onChange={(e) => setData('condition', e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    >
                                        <option value="">Selecione</option>
                                        {conditions.map((condition) => (
                                            <option key={condition.value} value={condition.value}>
                                                {condition.label}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Setor
                                    </label>
                                    <select
                                        value={data.sector_id}
                                        onChange={(e) => setData('sector_id', e.target.value)}
                                        className="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    >
                                        <option value="">Selecione</option>
                                        {sectors.map((sector) => (
                                            <option key={sector.id} value={sector.id}>
                                                {sector.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div className="flex justify-end space-x-3">
                            <a
                                href={route('assets.index')}
                                className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                            >
                                {processing ? (
                                    <svg className="animate-spin mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                ) : (
                                    <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                )}
                                {processing ? 'Salvando...' : 'Salvar'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SGAITILayout>
    );
}
