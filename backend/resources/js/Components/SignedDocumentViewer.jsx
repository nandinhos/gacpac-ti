import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import ConfirmationModal from './ConfirmationModal';

const SignedDocumentViewer = ({ custodyLog, onDocumentUploaded, onDocumentRemoved, canManage = false }) => {
    const [showUploadModal, setShowUploadModal] = useState(false);
    const [showRemoveModal, setShowRemoveModal] = useState(false);
    const [uploading, setUploading] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);

    const handleFileSelect = (event) => {
        console.log('Arquivo selecionado!', event.target.files[0]);
        const file = event.target.files[0];
        if (file) {
            console.log('Validando arquivo:', file.name, file.type, file.size);

            // Validar tipo de arquivo
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Apenas arquivos PDF, JPEG ou PNG são permitidos.');
                return;
            }

            // Validar tamanho (máximo 10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('O arquivo deve ter no máximo 10MB.');
                return;
            }

            console.log('Arquivo validado, abrindo modal...');
            setSelectedFile(file);
            setShowUploadModal(true);
        }
    };

    const handleConfirmUpload = (justification) => {
        if (!selectedFile) return;

        console.log('Iniciando upload...', {
            custodyId: custodyLog.id,
            fileName: selectedFile.name,
            fileSize: selectedFile.size,
            justification: justification
        });

        setUploading(true);

        router.post(`/custody/${custodyLog.id}/upload-signed-document`, {
            signed_document: selectedFile,
            justification: justification
        }, {
            forceFormData: true,
            onSuccess: () => {
                console.log('Upload bem-sucedido!');
                // alert('Documento enviado com sucesso!'); // Feedback visual já virá do flash message ou atualização da UI
                setSelectedFile(null);
                setShowUploadModal(false);
                onDocumentUploaded();
            },
            onError: (errors) => {
                console.error('Erro no upload:', errors);
                alert('Erro ao enviar documento: ' + (errors.upload || Object.values(errors).join(', ')));
            },
            onFinish: () => {
                console.log('Upload finalizado');
                setUploading(false);
            }
        });
    };

    const handleConfirmRemove = async (justification) => {
        // Usar Inertia router para remoção com CSRF automático
        router.delete(`/custody/${custodyLog.id}/remove-signed-document`, {
            data: { justification },
            onSuccess: (page) => {
                // Laravel mostrará a mensagem de sucesso automaticamente
                onDocumentRemoved();
            },
            onError: (errors) => {
                console.error('Erro na remoção:', errors);
                alert('Erro ao remover documento: ' + (errors.message || Object.values(errors).join(', ')));
            }
        });
    };

    const getFileIcon = (filename) => {
        if (!filename) return null;

        const extension = filename.split('.').pop()?.toLowerCase();

        if (extension === 'pdf') {
            return (
                <svg className="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            );
        } else {
            return (
                <svg className="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            );
        }
    };

    return (
        <div className="bg-white border border-gray-200 rounded-lg overflow-hidden">
            {/* Header */}
            <div className="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h3 className="text-lg font-semibold text-white flex items-center">
                    <svg className="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Documento Assinado
                </h3>
                <p className="text-green-100 text-sm mt-1">
                    Versão assinada da cautela para comprovação oficial
                </p>
            </div>

            <div className="p-6">
                {custodyLog.signed_document_url ? (
                    /* Documento existe */
                    <div className="space-y-4">
                        {/* Informações do documento */}
                        <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div className="flex items-start justify-between">
                                <div className="flex items-center space-x-3">
                                    {getFileIcon(custodyLog.signed_document_url)}
                                    <div>
                                        <h4 className="font-medium text-green-900">
                                            Documento Assinado Disponível
                                        </h4>
                                        <p className="text-sm text-green-700 mt-1">
                                            Cautela assinada e enviada em {' '}
                                            {custodyLog.signed_document_uploaded_at ?
                                                new Date(custodyLog.signed_document_uploaded_at).toLocaleString('pt-BR') :
                                                'Data não disponível'
                                            }
                                        </p>
                                        {custodyLog.signed_document_justification && (
                                            <p className="text-xs text-green-600 mt-1">
                                                <strong>Justificativa:</strong> {custodyLog.signed_document_justification}
                                            </p>
                                        )}
                                    </div>
                                </div>

                                <div className="flex items-center space-x-2">
                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        Assinado
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Ações disponíveis */}
                        <div className="flex flex-wrap gap-3">
                            <a
                                href={`/custody/${custodyLog.id}/signed-document`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                            >
                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Visualizar
                            </a>

                            <a
                                href={`/custody/${custodyLog.id}/signed-document/download`}
                                target="_blank"
                                className="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                            >
                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download
                            </a>

                            {canManage && (
                                <button
                                    onClick={() => setShowRemoveModal(true)}
                                    className="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                >
                                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remover
                                </button>
                            )}
                        </div>
                    </div>
                ) : (
                    /* Documento não existe */
                    <div className="space-y-4">
                        <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div className="flex items-center">
                                <svg className="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <div>
                                    <h4 className="font-medium text-yellow-900">
                                        Documento Assinado Pendente
                                    </h4>
                                    <p className="text-sm text-yellow-700 mt-1">
                                        A cautela foi criada, mas o documento assinado ainda não foi enviado.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Instruções e upload - Apenas para quem pode gerenciar */}
                        {canManage ? (
                            <div className="space-y-4">
                                <div className="text-sm text-gray-600">
                                    <h5 className="font-medium text-gray-900 mb-2">Para enviar o documento assinado:</h5>
                                    <ol className="list-decimal list-inside space-y-1 ml-4">
                                        <li>Exporte a cautela em PDF usando o botão "Exportar PDF"</li>
                                        <li>Imprima o documento</li>
                                        <li>Colete as assinaturas necessárias</li>
                                        <li>Digitalize ou fotografe o documento assinado</li>
                                        <li>Faça o upload do arquivo usando o botão abaixo</li>
                                    </ol>
                                </div>

                                <div className="flex items-center justify-center w-full">
                                    <label className="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div className="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg className="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p className="mb-2 text-sm text-gray-500">
                                                <span className="font-semibold">Clique para enviar</span> ou arraste o arquivo
                                            </p>
                                            <p className="text-xs text-gray-500">PDF, JPEG ou PNG (máx. 10MB)</p>
                                        </div>
                                        <input
                                            type="file"
                                            className="hidden"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            onChange={handleFileSelect}
                                            disabled={uploading}
                                        />
                                    </label>
                                </div>
                            </div>
                        ) : (
                            <p className="text-sm text-gray-500 italic">
                                Aguardando envio do documento assinado pelo administrador.
                            </p>
                        )}
                    </div>
                )}
            </div>

            {/* Modal de confirmação de upload */}
            <ConfirmationModal
                isOpen={showUploadModal}
                onClose={() => {
                    console.log('Modal fechado');
                    setShowUploadModal(false);
                    setSelectedFile(null);
                }}
                onConfirm={(justification) => {
                    console.log('Modal confirmado com justificativa:', justification);
                    handleConfirmUpload(justification);
                }}
                title="Enviar Documento Assinado"
                message={`Tem certeza que deseja enviar o arquivo "${selectedFile?.name}"? Este documento será armazenado como comprovação oficial da cautela.`}
                confirmText="Enviar Documento"
                type="success"
                requireJustification={true}
                justificationLabel="Descrição do documento"
                justificationPlaceholder="Ex: Cautela assinada pelo responsável e testemunhas, documento escaneado em alta qualidade, etc."
            />

            {/* Modal de confirmação de remoção */}
            <ConfirmationModal
                isOpen={showRemoveModal}
                onClose={() => setShowRemoveModal(false)}
                onConfirm={handleConfirmRemove}
                title="Remover Documento Assinado"
                message="Tem certeza que deseja remover o documento assinado? Esta ação não pode ser desfeita e a cautela ficará sem comprovação de assinatura."
                confirmText="Remover"
                type="danger"
                requireJustification={true}
                justificationLabel="Motivo da remoção"
                justificationPlaceholder="Ex: Documento incorreto, necessidade de nova assinatura, correção de dados, etc."
            />
        </div>
    );
};

export default SignedDocumentViewer;