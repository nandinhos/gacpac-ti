import React, { useState, useEffect, useCallback } from 'react';

interface PhotoGalleryModalProps {
  photos: string[];
  onClose: () => void;
}

const PhotoGalleryModal: React.FC<PhotoGalleryModalProps> = ({ photos, onClose }) => {
  const [currentIndex, setCurrentIndex] = useState(0);

  const goToPrevious = useCallback(() => {
    const isFirstSlide = currentIndex === 0;
    const newIndex = isFirstSlide ? photos.length - 1 : currentIndex - 1;
    setCurrentIndex(newIndex);
  }, [currentIndex, photos]);

  const goToNext = useCallback(() => {
    const isLastSlide = currentIndex === photos.length - 1;
    const newIndex = isLastSlide ? 0 : currentIndex + 1;
    setCurrentIndex(newIndex);
  }, [currentIndex, photos]);

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'ArrowLeft') {
        goToPrevious();
      } else if (e.key === 'ArrowRight') {
        goToNext();
      } else if (e.key === 'Escape') {
        onClose();
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => {
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, [goToPrevious, goToNext, onClose]);

  if (!photos || photos.length === 0) {
    return null;
  }

  return (
    <div 
        className="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50"
        onClick={onClose}
        role="dialog"
        aria-modal="true"
        aria-label="Galeria de Fotos"
    >
      <div 
        className="relative bg-white p-4 rounded-lg shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        <button
          onClick={onClose}
          className="absolute -top-3 -right-3 bg-white rounded-full p-1 text-gray-800 hover:bg-gray-200 z-10 shadow-lg"
          aria-label="Fechar galeria"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div className="flex-grow flex items-center justify-center relative min-h-0">
           <img src={photos[currentIndex]} alt={`Foto do ativo ${currentIndex + 1}`} className="max-w-full max-h-[75vh] object-contain" />
        </div>
        
        <div className="text-center text-sm text-gray-600 mt-2" aria-live="polite">
            Foto {currentIndex + 1} de {photos.length}
        </div>

        {photos.length > 1 && (
            <>
                <button 
                    onClick={goToPrevious}
                    className="absolute top-1/2 left-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Foto anterior"
                >
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button 
                    onClick={goToNext} 
                    className="absolute top-1/2 right-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Próxima foto"
                >
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </>
        )}
      </div>
    </div>
  );
};

export default PhotoGalleryModal;
