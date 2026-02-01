import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Edit({ log }) {
    const { data, setData, put, processing, errors } = useForm({
        notes: log.notes || '',
        termUrl: log.term_url || '',
        signedTermUrl: log.signed_term_url || '',
    });

    function submit(e) {
        e.preventDefault();
        put(route('custody.update', log.id));
    }

    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Editar Cautela: {log.cautela_number}
                </h2>
            }
        >
            <Head title={`Editar Cautela ${log.cautela_number}`} />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-md rounded-lg p-8">
                        <form onSubmit={submit}>
                            <div className="mb-4">
                                <label htmlFor="notes" className="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea 
                                    id="notes" 
                                    value={data.notes} 
                                    onChange={e => setData('notes', e.target.value)} 
                                    rows={4}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                ></textarea>
                                {errors.notes && <p className="text-red-500 text-xs mt-1">{errors.notes}</p>}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="termUrl" className="block text-sm font-medium text-gray-700">URL do Termo (em branco)</label>
                                <input 
                                    type="text" 
                                    id="termUrl" 
                                    value={data.termUrl} 
                                    onChange={e => setData('termUrl', e.target.value)}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                                {errors.termUrl && <p className="text-red-500 text-xs mt-1">{errors.termUrl}</p>}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="signedTermUrl" className="block text-sm font-medium text-gray-700">URL do Termo (assinado)</label>
                                <input 
                                    type="text" 
                                    id="signedTermUrl" 
                                    value={data.signedTermUrl} 
                                    onChange={e => setData('signedTermUrl', e.target.value)}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                                {errors.signedTermUrl && <p className="text-red-500 text-xs mt-1">{errors.signedTermUrl}</p>}
                            </div>

                            <div className="mt-8 flex justify-end space-x-4">
                                <Link href={route('custody.index')} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</Link>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50">
                                    {processing ? 'Salvando...' : 'Salvar Alterações'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
