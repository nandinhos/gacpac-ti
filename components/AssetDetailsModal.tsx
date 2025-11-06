// components/AssetDetailsModal.tsx
import React, { useState, useCallback, useEffect } from 'react';
import { Asset, Sector, MilitaryUser, AssetPhoto } from '../types';
import { assetsApi } from '../services/api';
import ConfirmationModal from './ConfirmationModal';

interface PhotoCarouselProps {
  assetId: string;
  photos: AssetPhoto[];
  onPhotoChange: () => void; // Callback to trigger a data refresh
}

const PhotoCarousel: React.FC<PhotoCarouselProps> = ({ assetId, photos, onPhotoChange }) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isUploading, setIsUploading] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  
  // Garantir que photos seja sempre um array
  const safePhotos = photos || [];

  const goToPrevious = () => {
    if (safePhotos.length === 0) return;
    const isFirstSlide = currentIndex === 0;
    const newIndex = isFirstSlide ? safePhotos.length - 1 : currentIndex - 1;
    setCurrentIndex(newIndex);
  };

  const goToNext = useCallback(() => {
    if (safePhotos.length === 0) return;
    const isLastSlide = currentIndex === safePhotos.length - 1;
    const newIndex = isLastSlide ? 0 : currentIndex + 1;
    setCurrentIndex(newIndex);
  }, [currentIndex, safePhotos.length]);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    console.log("🔍 Upload iniciado...", e.target.files);
    
    if (e.target.files) {
      const files = Array.from(e.target.files);
      console.log("📁 Arquivos selecionados:", files.length, files);
      
      if (files.length === 0) {
        console.log("❌ Nenhum arquivo selecionado");
        return;
      }

      setIsUploading(true);
      console.log("🚀 Iniciando upload para asset:", assetId);
      
      try {
        const results = await Promise.all(files.map(async (file, index) => {
          console.log(`📤 Enviando arquivo ${index + 1}:`, file.name, file.size, file.type);
          const result = await assetsApi.addPhoto(assetId, file);
          console.log(`✅ Arquivo ${index + 1} enviado:`, result);
          return result;
        }));
        
        console.log("🎉 Todos os uploads concluídos:", results);
        onPhotoChange(); // Notify parent to refetch data
        alert(`${files.length} foto(s) enviada(s) com sucesso!`);
      } catch (error) {
        console.error("❌ Erro no upload de fotos:", error);
        alert(`Falha ao enviar fotos: ${error.message || error}`);
      } finally {
        setIsUploading(false);
        // Limpar o input para permitir reenvio do mesmo arquivo
        e.target.value = '';
      }
    }
  };
  
  const handleDelete = () => {
    if (safePhotos.length === 0) return;
    setShowDeleteModal(true);
  };

  const handleConfirmDelete = async () => {
    const photoToDelete = safePhotos[currentIndex];
    try {
        await assetsApi.deletePhoto(assetId, photoToDelete.id);
        onPhotoChange();
        alert("Foto excluída com sucesso!");
    } catch (error) {
        console.error("Erro ao excluir foto:", error);
        throw error; // Para ser tratado pelo modal
    }
  };

  return (
    <div className="w-full">
        <div className="relative h-64 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
        {safePhotos.length > 0 ? (
            <>
            <img src={safePhotos[currentIndex].url} alt={`Foto do ativo ${currentIndex + 1}`} className="w-full h-full object-contain" />
            {safePhotos.length > 1 && (
                 <>
                    <button onClick={goToPrevious} className="absolute left-2 top-1/2 -translate-y-1/2 p-1 bg-black bg-opacity-50 text-white rounded-full hover:bg-opacity-75">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button onClick={goToNext} className="absolute right-2 top-1/2 -translate-y-1/2 p-1 bg-black bg-opacity-50 text-white rounded-full hover:bg-opacity-75">
                         <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </>
            )}
            <button onClick={handleDelete} title="Excluir Foto" className="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 bg-opacity-75 hover:bg-opacity-100">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div className="absolute bottom-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs bg-black bg-opacity-50 text-white rounded">
                {currentIndex + 1} / {safePhotos.length}
            </div>
            </>
        ) : (
            <p className="text-gray-500">Nenhuma foto cadastrada</p>
        )}
        </div>
        <div className="mt-4">
            <label htmlFor="photo-upload" className={`w-full text-center px-4 py-2 ${isUploading ? 'bg-gray-600' : 'bg-blue-600 hover:bg-blue-700'} text-white rounded-md transition cursor-pointer flex items-center justify-center`}>
                {isUploading ? (
                  <>
                    <svg className="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Enviando...
                  </>
                ) : (
                  <>
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Adicionar Fotos
                  </>
                )}
            </label>
            <input 
              id="photo-upload" 
              type="file" 
              multiple 
              accept="image/*" 
              className="hidden" 
              onChange={handleFileChange}
              disabled={isUploading}
            />
        </div>

        {/* Modal de Confirmação para Exclusão de Foto */}
        <ConfirmationModal
          isOpen={showDeleteModal}
          onClose={() => setShowDeleteModal(false)}
          onConfirm={handleConfirmDelete}
          title="Excluir Foto"
          message={`Tem certeza que deseja excluir esta foto? Esta ação não pode ser desfeita.`}
          confirmText="Excluir"
          type="danger"
          requireJustification={true}
          justificationLabel="Motivo da exclusão"
          justificationPlaceholder="Ex: Foto incorreta, duplicada, sem qualidade, etc."
        />
    </div>
  );
};


interface AssetDetailsModalProps {
  asset: Asset;
  sectors: Sector[];
  users: MilitaryUser[];
  onClose: () => void;
  onUpdateAsset: (updatedAsset: Asset) => void;
}

const DetailItem: React.FC<{ label: string; value?: string | number }> = ({ label, value }) => (
    <div>
        <p className="text-sm font-medium text-gray-500">{label}</p>
        <p className="text-md text-gray-800">{value || 'N/A'}</p>
    </div>
);


const AssetDetailsModal: React.FC<AssetDetailsModalProps> = ({ asset, sectors, users, onClose, onUpdateAsset }) => {
    const [currentAsset, setCurrentAsset] = useState<Asset>(asset);
    const sector = sectors.find(s => s.id === currentAsset.sector_id)?.name;
    const custodian = users.find(u => u.id === currentAsset.custodian_user_id);
    const custodianName = custodian ? `${custodian.rank} ${custodian.name}` : undefined;

    // Carregar dados atualizados ao abrir o modal
    useEffect(() => {
        const loadAssetData = async () => {
            try {
                console.log("🔄 Carregando dados atualizados do asset ao abrir modal...");
                const updatedAsset = await assetsApi.getById(String(asset.id));
                console.log("✅ Dados atualizados carregados:", updatedAsset);
                setCurrentAsset(updatedAsset);
            } catch (error) {
                console.error("❌ Erro ao carregar dados do asset:", error);
                setCurrentAsset(asset); // Fallback para o asset original
            }
        };

        loadAssetData();
    }, [asset.id]);

    const handlePhotoChange = async () => {
        console.log("📡 onPhotoChange chamado, recarregando dados do asset...");
        try {
            // Buscar dados atualizados do asset
            const updatedAsset = await assetsApi.getById(String(currentAsset.id));
            console.log("✅ Asset atualizado recebido:", updatedAsset);
            setCurrentAsset(updatedAsset); // Atualiza o estado local
            onUpdateAsset(updatedAsset); // Atualiza o asset no componente pai
        } catch (error) {
            console.error("❌ Erro ao recarregar asset:", error);
        }
    };

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" onClick={onClose}>
        <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" onClick={e => e.stopPropagation()}>
            <div className="flex justify-between items-start mb-4">
                <div>
                    <h2 className="text-2xl font-bold text-gray-800">{currentAsset.name}</h2>
                    <p className="text-sm text-gray-500 font-mono">{currentAsset.qr_code}</p>
                </div>
                 <button onClick={onClose} className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800" aria-label="Fechar">
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div className="flex-grow overflow-y-auto pr-4 -mr-4">
                 <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="md:col-span-1 space-y-4 text-center">
                        <img 
                            src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentAsset.qr_code)}`}
                            alt={`QR Code for ${currentAsset.qr_code}`}
                            className="mx-auto border p-1"
                        />
                        <a 
                            href={`https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(currentAsset.qr_code)}`}
                            download={`${currentAsset.qr_code}.png`}
                            className="inline-block mt-2 text-sm text-blue-600 hover:underline"
                        >
                            Baixar QR Code
                        </a>
                    </div>
                    <div className="md:col-span-2 grid grid-cols-2 gap-x-6 gap-y-4 bg-gray-50 p-4 rounded-lg">
                        <DetailItem label="Categoria" value={currentAsset.category} />
                        <DetailItem label="Situação" value={currentAsset.status} />
                        <DetailItem label="Nº de Série" value={currentAsset.serial_number} />
                        <DetailItem label="Nº de Patrimônio" value={currentAsset.patrimony_id} />
                        <DetailItem label="Setor Atual" value={sector} />
                        <DetailItem label="Cautelado por" value={custodianName} />
                        <DetailItem label="Data de Aquisição" value={currentAsset.acquisition_date ? new Date(currentAsset.acquisition_date).toLocaleDateString('pt-BR') : undefined} />
                        <DetailItem label="Fim da Garantia" value={currentAsset.warranty_expiry ? new Date(currentAsset.warranty_expiry).toLocaleDateString('pt-BR') : undefined} />
                        <DetailItem label="Conta" value={currentAsset.conta} />
                        <DetailItem label="Categoria do Inventário" value={currentAsset.categoria_inventario} />
                        <DetailItem label="BMP" value={currentAsset.bmp} />
                        <DetailItem label="Componente" value={currentAsset.componente} />
                        <DetailItem label="Situação do Inventário" value={currentAsset.situacao} />
                        <DetailItem label="Quantidade" value={currentAsset.qtd} />
                        <DetailItem label="Valor Atualizado" value={currentAsset.valor_atualizado} />
                        <DetailItem label="Depreciação Acumulada" value={currentAsset.deprec_acumulada} />
                        <DetailItem label="Valor Líquido" value={currentAsset.valor_liquido} />
                    </div>
                    <div className="md:col-span-3">
                         <h3 className="text-xl font-semibold text-gray-700 mb-2 border-b pb-2">Fotos do Ativo</h3>
                         <PhotoCarousel 
                            assetId={String(currentAsset.id)}
                            photos={currentAsset.photos}
                            onPhotoChange={handlePhotoChange}
                         />
                    </div>
                 </div>
            </div>

            <div className="mt-8 flex justify-end">
                <button onClick={onClose} className="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Fechar</button>
            </div>
        </div>
        </div>
    );
};

export default AssetDetailsModal;