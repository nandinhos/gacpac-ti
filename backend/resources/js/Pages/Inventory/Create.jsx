import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create({ users, sectors }) {
    const { data, setData, post, processing, errors } = useForm({
        sector_id: '',
        responsible_user_id: '',
        commission_number: '',
        start_date: new Date().toISOString().split('T')[0],
    });

    function submit(e) {
        e.preventDefault();
        post(route('inventory.store'));
    }

    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Iniciar Novo Inventário
                </h2>
            }
        >
            <Head title="Novo Inventário - SGAITI" />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-md rounded-lg p-8">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="sector_id" className="block text-sm font-medium text-gray-700">Setor</label>
                                    <select 
                                        id="sector_id" 
                                        value={data.sector_id} 
                                        onChange={e => setData('sector_id', e.target.value)} 
                                        required 
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    >
                                        <option value="">Selecione um setor ou Global</option>
                                        <option value="global">Inventário Global (Todos os Setores)</option>
                                        {sectors.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
                                    </select>
                                    {errors.sector_id && <p className="text-red-500 text-xs mt-1">{errors.sector_id}</p>}
                                </div>
                                <div>
                                    <label htmlFor="responsible_user_id" className="block text-sm font-medium text-gray-700">Responsável</label>
                                    <select 
                                        id="responsible_user_id" 
                                        value={data.responsible_user_id} 
                                        onChange={e => setData('responsible_user_id', e.target.value)} 
                                        required 
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    >
                                        <option value="">Selecione um militar</option>
                                        {users.filter(u => u.is_active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
                                    </select>
                                    {errors.responsible_user_id && <p className="text-red-500 text-xs mt-1">{errors.responsible_user_id}</p>}
                                </div>
                                <div>
                                    <label htmlFor="commission_number" className="block text-sm font-medium text-gray-700">Nº da Comissão (Opcional)</label>
                                    <input 
                                        type="text" 
                                        id="commission_number" 
                                        value={data.commission_number} 
                                        onChange={e => setData('commission_number', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                    {errors.commission_number && <p className="text-red-500 text-xs mt-1">{errors.commission_number}</p>}
                                </div>
                                <div>
                                    <label htmlFor="start_date" className="block text-sm font-medium text-gray-700">Data de Início</label>
                                    <input 
                                        type="date" 
                                        id="start_date" 
                                        value={data.start_date} 
                                        onChange={e => setData('start_date', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        required
                                    />
                                    {errors.start_date && <p className="text-red-500 text-xs mt-1">{errors.start_date}</p>}
                                </div>
                            </div>

                            <div className="mt-8 flex justify-end space-x-4">
                                <Link href={route('inventory.index')} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</Link>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50">
                                    {processing ? 'Iniciando...' : 'Iniciar Inventário'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
