import React, { useState } from 'react';
import { CustodyLog, MilitaryUser, Asset } from '../types';

interface CustodyDetailsModalProps {
  log: CustodyLog;
  user: MilitaryUser;
  assetsInLog: Asset[];
  onClose: () => void;
  onDischarge: (logId: number) => void;
  onUploadSignedTerm: (logId: number, fileUrl: string) => void;
}

const CustodyDetailsModal: React.FC<CustodyDetailsModalProps> = ({ log, user, assetsInLog, onClose, onDischarge, onUploadSignedTerm }) => {
  const [isUploading, setIsUploading] = useState(false);

  const handlePrint = () => {
    const jsPDF = (window as any).jsPDF;
    const doc = new jsPDF();
    const pageHeight = doc.internal.pageSize.height;
    
    // Header
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('TERMO DE RESPONSABILIDADE DE MATERIAL', doc.internal.pageSize.width / 2, 20, { align: 'center' });
    doc.setFontSize(14);
    doc.text(`Nº: ${log.cautelaNumber}`, doc.internal.pageSize.width / 2, 28, { align: 'center' });
    
    // User Info
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    const userInfo = `Eu, ${user.rank} ${user.name}, Idt Mil ${user.militaryId}, declaro ter recebido o(s) material(is) de TI listado(s) abaixo, sob minha responsabilidade, em perfeito estado de funcionamento.`;
    const splitUserInfo = doc.splitTextToSize(userInfo, 180);
    doc.text(splitUserInfo, 15, 40);

    // Asset Table
    const tableColumn = ["QR Code", "Tipo/Modelo", "Nº de Série", "Patrimônio"];
    const tableRows: (string|undefined)[][] = assetsInLog.map(asset => [asset.qrCode, asset.type, asset.serialNumber, asset.patrimonyId]);
    (doc as any).autoTable({
      head: [tableColumn],
      body: tableRows,
      startY: 60,
    });
    
    const finalY = (doc as any).lastAutoTable.finalY || 80;

    // Signatures
    const signatureY = pageHeight - 50 > finalY + 30 ? pageHeight - 50 : finalY + 30;
    doc.text('___________________________________________', 15, signatureY);
    doc.text(`${user.rank} ${user.name}`, 15, signatureY + 5);
    
    doc.text('___________________________________________', doc.internal.pageSize.width - 80, signatureY);
    doc.text('Chefe da Seção de TI', doc.internal.pageSize.width - 80, signatureY + 5);

    // Footer
    doc.setFontSize(10);
    doc.text(`Gerado em: ${new Date().toLocaleString('pt-BR')}`, 15, pageHeight - 10);
    
    doc.save(`termo_cautela_${log.cautelaNumber.replace(/\//g, '-')}.pdf`);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      const file = e.target.files[0];
      setIsUploading(true);
      // Simulate upload delay
      setTimeout(() => {
        const fakeUrl = `/terms/signed/${file.name}`;
        onUploadSignedTerm(log.id, fakeUrl);
        setIsUploading(false);
      }, 1000);
    }
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" onClick={onClose}>
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" onClick={(e) => e.stopPropagation()}>
        <div className="flex justify-between items-start mb-4">
          <div>
            <h2 className="text-2xl font-bold text-gray-800">Detalhes da Cautela - {log.cautelaNumber}</h2>
            <p className="text-gray-600">Responsável: {user.rank} {user.name}</p>
          </div>
          <button onClick={onClose} className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800" aria-label="Fechar">
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div className="flex-grow overflow-y-auto mb-6 pr-4 -mr-4">
          <div className="grid grid-cols-2 gap-4 text-sm mb-4">
            <div><strong>Data da Cautela:</strong> {new Date(log.checkoutDate).toLocaleDateString('pt-BR')}</div>
            <div><strong>Status:</strong> {log.checkinDate ? <span className="font-bold text-gray-600">Concluída em {new Date(log.checkinDate).toLocaleDateString('pt-BR')}</span> : <span className="font-bold text-green-600">Ativa</span>}</div>
          </div>
          
          <h3 className="text-lg font-semibold text-gray-700 mb-2">Itens Cautelados</h3>
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">QR Code</th>
                <th scope="col" className="px-6 py-3">Tipo/Modelo</th>
                <th scope="col" className="px-6 py-3">Categoria</th>
                <th scope="col" className="px-6 py-3">Patrimônio</th>
                <th scope="col" className="px-6 py-3">Nº de Série</th>
              </tr>
            </thead>
            <tbody>
              {assetsInLog.map(asset => (
                <tr key={asset.id} className="bg-white border-b hover:bg-gray-50">
                  <td className="px-6 py-4 font-mono">{asset.qrCode}</td>
                  <td className="px-6 py-4 font-medium text-gray-900">{asset.type}</td>
                  <td className="px-6 py-4">{asset.category}</td>
                  <td className="px-6 py-4">{asset.patrimonyId || 'N/A'}</td>
                  <td className="px-6 py-4">{asset.serialNumber}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Actions Footer */}
        <div className="mt-auto pt-6 border-t">
          {!log.checkinDate && (
             <div className="bg-gray-50 p-4 rounded-lg">
                <h3 className="font-semibold text-gray-800 mb-2">Processo de Devolução (Baixa da Cautela)</h3>
                <div className="flex items-center space-x-4">
                    {!log.signedTermUrl ? (
                         <div className="flex-1">
                            <label htmlFor="upload-term" className="block text-sm font-medium text-gray-700 mb-1">Passo 1 (Opcional): Anexar Termo Assinado</label>
                            <input id="upload-term" type="file" accept="application/pdf" onChange={handleFileChange} className="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                             {isUploading && <p className="text-sm text-blue-600 mt-1">Enviando...</p>}
                        </div>
                    ) : (
                        <div className="flex-1">
                            <p className="text-sm font-medium text-gray-700">Termo Assinado Anexado:</p>
                            <a href={log.signedTermUrl} target="_blank" rel="noopener noreferrer" className="text-sm text-blue-600 hover:underline">{log.signedTermUrl.split('/').pop()}</a>
                        </div>
                    )}
                   <div className="flex-1">
                        <p className="block text-sm font-medium text-gray-700 mb-1">Passo 2: Dar Baixa no Sistema</p>
                        <button onClick={() => onDischarge(log.id)} disabled={isUploading} className="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Dar Baixa na Cautela
                        </button>
                   </div>
                </div>
             </div>
          )}
          <div className="mt-6 flex justify-between items-center">
              <button onClick={handlePrint} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm flex items-center">
                  <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-8V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0h2m-2 0h-2m-2 0h2m-2 0h-2"></path></svg>
                  Imprimir Termo
              </button>
              <button onClick={onClose} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Fechar</button>
          </div>
        </div>

      </div>
    </div>
  );
};

export default CustodyDetailsModal;