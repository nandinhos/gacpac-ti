// components/AssetDetailsModal.tsx
import React, { useState, useCallback } from 'react';
import { Asset, Sector, MilitaryUser } from '../types';

interface PhotoCarouselProps {
  photos: string[];
  onAddPhotos: (newPhotos: string[]) => void;
  onDeletePhoto: (index: number) => void;
}

const PhotoCarousel: React.FC<PhotoCarouselProps> = ({ photos, onAddPhotos, onDeletePhoto }) => {
  const [currentIndex, setCurrentIndex] = useState(0);

  const goToPrevious = () => {
    if (photos.length === 0) return;
    const isFirstSlide = currentIndex === 0;
    const newIndex = isFirstSlide ? photos.length - 1 : currentIndex - 1;
    setCurrentIndex(newIndex);
  };

  const goToNext = useCallback(() => {
    if (photos.length === 0) return;
    const isLastSlide = currentIndex === photos.length - 1;
    const newIndex = isLastSlide ? 0 : currentIndex + 1;
    setCurrentIndex(newIndex);
  }, [currentIndex, photos.length]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      // FIX: Explicitly type `files` as `File[]` to correct type inference.
      const files: File[] = Array.from(e.target.files);
      const newPhotoUrls: string[] = [];
      let filesProcessed = 0;

      if (files.length === 0) return;

      files.forEach(file => {
        const reader = new FileReader();
        reader.onloadend = () => {
          newPhotoUrls.push(reader.result as string);
          filesProcessed++;
          if (filesProcessed === files.length) {
            onAddPhotos(newPhotoUrls);
          }
        };
        reader.readAsDataURL(file);
      });
    }
  };
  
  const handleDelete = () => {
    if (window.confirm('Tem certeza que deseja excluir esta foto?')) {
        onDeletePhoto(currentIndex);
        if (currentIndex >= photos.length - 1 && photos.length > 1) {
            setCurrentIndex(currentIndex - 1);
        } else if (photos.length <= 1) {
            setCurrentIndex(0);
        }
    }
  }

  return (
    <div className="w-full">
        <div className="relative h-64 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
        {photos.length > 0 ? (
            <>
            <img src={photos[currentIndex]} alt={`Foto do ativo ${currentIndex + 1}`} className="w-full h-full object-contain" />
            {photos.length > 1 && (
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
                {currentIndex + 1} / {photos.length}
            </div>
            </>
        ) : (
            <p className="text-gray-500">Nenhuma foto cadastrada</p>
        )}
        </div>
        <div className="mt-4">
            <label htmlFor="photo-upload" className="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition cursor-pointer flex items-center justify-center">
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Adicionar Fotos
            </label>
            <input id="photo-upload" type="file" multiple accept="image/*" className="hidden" onChange={handleFileChange} />
        </div>
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
    const sector = sectors.find(s => s.id === asset.currentSectorId)?.name;
    const custodian = users.find(u => u.id === asset.custodianUserId);
    const custodianName = custodian ? `${custodian.rank} ${custodian.name}` : undefined;

    const handleAddPhotos = (newPhotos: string[]) => {
        const updatedAsset = { ...asset, photos: [...asset.photos, ...newPhotos] };
        onUpdateAsset(updatedAsset);
    };
    
    const handleDeletePhoto = (index: number) => {
        const updatedPhotos = asset.photos.filter((_, i) => i !== index);
        const updatedAsset = { ...asset, photos: updatedPhotos };
        onUpdateAsset(updatedAsset);
    };

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" onClick={onClose}>
        <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" onClick={e => e.stopPropagation()}>
            <div className="flex justify-between items-start mb-4">
                <div>
                    <h2 className="text-2xl font-bold text-gray-800">{asset.type}</h2>
                    <p className="text-sm text-gray-500 font-mono">{asset.qrCode}</p>
                </div>
                 <button onClick={onClose} className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800" aria-label="Fechar">
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div className="flex-grow overflow-y-auto pr-4 -mr-4">
                 <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="md:col-span-1 space-y-4 text-center">
                        <img 
                            src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(asset.qrCode)}`}
                            alt={`QR Code for ${asset.qrCode}`}
                            className="mx-auto border p-1"
                        />
                        <a 
                            href={`https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(asset.qrCode)}`}
                            download={`${asset.qrCode}.png`}
                            className="inline-block mt-2 text-sm text-blue-600 hover:underline"
                        >
                            Baixar QR Code
                        </a>
                    </div>
                    <div className="md:col-span-2 grid grid-cols-2 gap-x-6 gap-y-4 bg-gray-50 p-4 rounded-lg">
                        <DetailItem label="Categoria" value={asset.category} />
                        <DetailItem label="Situação" value={asset.status} />
                        <DetailItem label="Nº de Série" value={asset.serialNumber} />
                        <DetailItem label="Nº de Patrimônio" value={asset.patrimonyId} />
                        <DetailItem label="Setor Atual" value={sector} />
                        <DetailItem label="Cautelado por" value={custodianName} />
                        <DetailItem label="Data de Aquisição" value={new Date(asset.acquisitionDate).toLocaleDateString('pt-BR')} />
                        <DetailItem label="Fim da Garantia" value={asset.warrantyEndDate ? new Date(asset.warrantyEndDate).toLocaleDateString('pt-BR') : undefined} />
                    </div>
                    <div className="md:col-span-3">
                         <h3 className="text-xl font-semibold text-gray-700 mb-2 border-b pb-2">Fotos do Ativo</h3>
                         <PhotoCarousel 
                            photos={asset.photos}
                            onAddPhotos={handleAddPhotos}
                            onDeletePhoto={handleDeletePhoto}
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