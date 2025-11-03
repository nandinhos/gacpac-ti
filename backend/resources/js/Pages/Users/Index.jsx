import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head } from '@inertiajs/react';

export default function Index() {
    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Gestão de Usuários
                </h2>
            }
        >
            <Head title="Usuários - SGAITI" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow rounded-lg">
                        <div className="px-4 py-5 sm:p-6">
                            <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Sistema de Usuários
                            </h3>
                            <p className="text-gray-600">
                                Módulo para gerenciamento de usuários militares e permissões.
                            </p>
                            <div className="mt-4">
                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Em desenvolvimento
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
