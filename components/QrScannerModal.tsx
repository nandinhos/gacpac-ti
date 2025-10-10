import React, { useRef, useEffect, useState } from 'react';

declare const jsQR: any;

interface QrScannerModalProps {
  onScanSuccess: (data: string) => void;
  onClose: () => void;
}

const QrScannerModal: React.FC<QrScannerModalProps> = ({ onScanSuccess, onClose }) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [error, setError] = useState<string | null>(null);
  const animationFrameId = useRef<number | null>(null);

  useEffect(() => {
    let stream: MediaStream | null = null;

    const tick = () => {
      if (videoRef.current && videoRef.current.readyState === videoRef.current.HAVE_ENOUGH_DATA) {
        if (canvasRef.current) {
          const canvas = canvasRef.current.getContext('2d');
          if (canvas) {
            canvasRef.current.height = videoRef.current.videoHeight;
            canvasRef.current.width = videoRef.current.videoWidth;
            canvas.drawImage(videoRef.current, 0, 0, canvasRef.current.width, canvasRef.current.height);
            const imageData = canvas.getImageData(0, 0, canvasRef.current.width, canvasRef.current.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
              inversionAttempts: 'dontInvert',
            });

            if (code) {
              onScanSuccess(code.data);
              onClose();
              return;
            }
          }
        }
      }
      animationFrameId.current = requestAnimationFrame(tick);
    };

    const startCamera = async () => {
      try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        if (videoRef.current) {
          videoRef.current.srcObject = stream;
          videoRef.current.setAttribute('playsinline', 'true'); // Required for iOS
          videoRef.current.play();
          animationFrameId.current = requestAnimationFrame(tick);
        }
      } catch (err) {
        console.error("Error accessing camera: ", err);
        setError("Não foi possível acessar a câmera. Verifique as permissões do navegador.");
      }
    };

    startCamera();

    return () => {
      if (animationFrameId.current) {
        cancelAnimationFrame(animationFrameId.current);
      }
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    };
  }, [onScanSuccess, onClose]);

  return (
    <div
      className="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-label="Leitor de QR Code"
    >
      <style>{`
        @keyframes scan {
          0% { top: 0; }
          100% { top: 100%; }
        }
        .scanner-line {
          animation: scan 3s ease-in-out infinite alternate;
        }
      `}</style>
      <div
        className="relative bg-gray-900 p-4 rounded-lg shadow-2xl w-full max-w-md"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="relative w-full overflow-hidden rounded-md" style={{ paddingTop: '100%' }}>
            <video ref={videoRef} className="absolute top-0 left-0 w-full h-full object-cover" />
            <div className="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                <div className="w-3/4 h-3/4 border-4 border-dashed border-white border-opacity-50 rounded-lg relative overflow-hidden">
                    <div className="scanner-line absolute top-0 left-0 w-full h-0.5 bg-red-500 shadow-[0_0_10px_1px_rgba(239,68,68,0.7)]"></div>
                </div>
            </div>
        </div>
        
        <canvas ref={canvasRef} style={{ display: 'none' }} />
        
        {error ? (
          <p className="text-white text-center mt-4 bg-red-500 p-2 rounded">{error}</p>
        ) : (
          <p className="text-white text-center mt-4">Aponte a câmera para o QR Code</p>
        )}

        <button
          onClick={onClose}
          className="absolute -top-3 -right-3 bg-white rounded-full p-1 text-gray-800 hover:bg-gray-200 z-10 shadow-lg"
          aria-label="Fechar leitor"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
    </div>
  );
};

export default QrScannerModal;
