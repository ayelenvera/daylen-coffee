import{g as c,d as x,e as z,u as D,Z as N,w as P,f as t,q as M,t as r,j as i,F as C,h as $,n as T,i as f}from"./app-BYhsu56_.js";import{_ as L}from"./AuthenticatedLayout-B6T8qzH8.js";import"./HelpLink-0Gkg05qS.js";const _={class:"py-12 relative"},B={class:"max-w-5xl mx-auto sm:px-6 lg:px-8 transform transition-all duration-300 hover:scale-[1.01]"},I={class:"bg-white shadow-2xl rounded-xl overflow-hidden border border-amber-100",id:"invoice-content"},A={class:"bg-gradient-to-r from-amber-600 to-amber-700 text-white py-8 px-10 relative overflow-hidden"},H={class:"relative z-10"},V={class:"flex flex-col md:flex-row md:justify-between md:items-start"},R={class:"text-right"},Y={class:"inline-block bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl font-bold text-lg text-white shadow-lg"},G={class:"text-amber-100 text-sm mt-2"},U={class:"p-10"},q={class:"grid grid-cols-1 md:grid-cols-2 gap-8 mb-10"},O={class:"bg-blue-50 p-6 rounded-xl border border-blue-100"},K={class:"space-y-2 text-gray-600 pl-11"},W={class:"flex items-start"},Z={class:"flex items-start"},J={class:"flex items-start"},Q={class:"flex items-start"},X={class:"flex items-start"},tt={class:"mb-10"},et={class:"overflow-hidden rounded-xl border border-gray-200 shadow-sm"},ot={class:"min-w-full divide-y divide-gray-200"},st={class:"bg-white divide-y divide-gray-200"},rt={class:"px-6 py-4"},nt={class:"flex items-center"},at={class:"flex-shrink-0 h-10 w-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3"},it={class:"text-amber-600"},dt={class:"text-sm font-medium text-gray-900"},lt={key:0,class:"text-xs text-gray-500 mt-1 space-x-2"},pt={key:0,class:"inline-flex items-center px-2 py-0.5 rounded bg-amber-100 text-amber-800"},mt={key:1,class:"inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800"},ct={class:"px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 font-medium"},xt={class:"inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100"},gt={class:"px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700"},ft={class:"px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900"},bt={class:"font-semibold"},ut={class:"bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-6 mb-10 shadow-sm"},ht={class:"max-w-md ml-auto"},yt={class:"space-y-3 mb-4"},wt={class:"flex justify-between text-gray-700"},vt={class:"flex justify-between text-gray-700"},zt={class:"flex justify-between text-xl font-bold text-gray-900"},kt={class:"text-2xl text-amber-700"},Dt={class:"mt-6 p-4 bg-white rounded-lg border border-green-100"},Pt={class:"flex items-center"},Ct={class:"ml-3"},jt={class:"mt-1 text-sm text-green-700"},Ft={key:0,class:"block text-xs text-green-600"},Et={key:0,class:"mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100"},St={class:"flex items-start"},Nt={class:"ml-3"},Mt={class:"mt-1 text-sm text-blue-700"},$t={class:"mt-12 pt-8 border-t border-gray-200"},Tt={class:"text-center"},Lt={class:"mt-2 text-xs text-gray-400"},At={__name:"Invoice",props:{order:{type:Object,required:!0}},setup(a){const s=a,j=l=>new Date(l).toLocaleDateString("es-ES",{year:"numeric",month:"long",day:"numeric",hour:"2-digit",minute:"2-digit"}),F=l=>{const e={year:"numeric",month:"long",day:"numeric",hour:"2-digit",minute:"2-digit",second:"2-digit",hour12:!0};return new Date(l).toLocaleString("es-ES",e)},E=async()=>{var l,e;try{if(console.log("🎯 Iniciando conversión directa HTML a PDF para pedido:",s.order.id),!s.order||!s.order.id)throw new Error("No se encontraron los datos del pedido. Por favor, recarga la página.");const n=document.createElement("div");n.innerHTML=`
      <div style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: #f59e0b;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
      ">
        <div style="width: 16px; height: 16px; border: 2px solid white; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        Generando PDF...
      </div>
      <style>
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      </style>
    `,document.body.appendChild(n);const p=o=>{try{return o?new Date(o).toLocaleDateString("es-ES",{year:"numeric",month:"long",day:"numeric",hour:"2-digit",minute:"2-digit"}):"Fecha no disponible"}catch(m){return console.error("❌ Error formateando fecha:",m),"Fecha no disponible"}};if(console.log("📦 Cargando librerías para conversión HTML a PDF..."),typeof window.html2canvas>"u"){const o=document.createElement("script");o.src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js",document.head.appendChild(o),await new Promise((m,g)=>{o.onload=m,o.onerror=()=>g(new Error("No se pudo cargar html2canvas")),setTimeout(()=>g(new Error("Timeout cargando html2canvas")),1e4)})}if(typeof window.jspdf>"u"){const o=document.createElement("script");o.src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js",document.head.appendChild(o),await new Promise((m,g)=>{o.onload=m,o.onerror=()=>g(new Error("No se pudo cargar jsPDF")),setTimeout(()=>g(new Error("Timeout cargando jsPDF")),1e4)})}console.log("✅ Librerías cargadas exitosamente"),console.log("📝 Creando contenido del PDF...");const d=document.createElement("div");d.style.position="absolute",d.style.left="-9999px",d.style.top="-9999px",d.style.width="794px",d.style.background="white",d.style.fontFamily="Arial, sans-serif",d.innerHTML=`
      <div style="font-family: Arial, sans-serif; color: #333; max-width: 794px; margin: 0 auto; background: white; padding: 25px;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 15px; text-align: center; margin-bottom: 15px; border-radius: 10px;">
          <h1 style="font-size: 20px; margin: 0; margin-bottom: 6px; font-weight: bold;">☕ Daylen Cafetería</h1>
          <p style="font-size: 12px; margin: 0; opacity: 0.9; margin-bottom: 8px;">Sistema de Facturación Electrónica</p>
          <div style="background: white; color: #d97706; padding: 6px 15px; border-radius: 3px; font-weight: bold; font-size: 14px; display: inline-block;">
            FACTURA N° ${s.order.id.toString().padStart(6,"0")}
          </div>
        </div>

        <!-- Información de la Empresa -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de la Empresa</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Empresa:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">Daylen Cafetería</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">RUC:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">12345678-9</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">+595 986 195914</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">daylencoffee@gmail.com</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${p(new Date)}</td>
            </tr>
          </table>
        </div>

        <!-- Información del Cliente -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información del Cliente</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Cliente:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${((l=s.order.user)==null?void 0:l.name)||"Cliente no disponible"}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${((e=s.order.user)==null?void 0:e.email)||"Email no disponible"}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Nombre de Entrega:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${s.order.customer_name||"No especificado"}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${s.order.phone||"No especificado"}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${s.order.address||"No especificada"}</td>
            </tr>
          </table>
        </div>

        <!-- Detalles del Pedido -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Detalles del Pedido</h3>
          <table style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; margin-bottom: 10px;">
            <thead>
              <tr style="background: #f9fafb;">
                <th style="padding: 6px; text-align: left; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; font-size: 9px;">Producto</th>
                <th style="padding: 6px; text-align: center; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 60px; font-size: 9px;">Cant.</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Precio</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${s.order.order_items&&s.order.order_items.length>0?s.order.order_items.map(o=>{var m;return`
                <tr>
                  <td style="padding: 6px; border: 1px solid #e5e7eb; color: #374151; font-size: 9px;">
                    <strong>${((m=o.product)==null?void 0:m.name)||"Producto eliminado"}</strong>
                    ${o.size||o.sugar?`<br><small style="color: #6b7280; font-size: 8px;">${o.size||""} ${o.size&&o.sugar?"•":""} ${o.sugar||""}</small>`:""}
                  </td>
                  <td style="padding: 6px; text-align: center; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">${o.quantity||0}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(o.price||0).toLocaleString("es-PY")}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(o.subtotal||0).toLocaleString("es-PY")}</td>
                </tr>
              `}).join(""):'<tr><td colspan="4" style="padding: 12px; text-align: center; color: #999; border: 1px solid #e5e7eb; font-size: 9px;">No hay productos disponibles</td></tr>'}
            </tbody>
          </table>
        </div>

        <!-- Totales -->
        <div style="background: #f9fafb; padding: 12px; margin: 12px 0; border-radius: 4px; border: 1px solid #e5e7eb;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Subtotal:</span>
            <span style="color: #374151; font-weight: 500;">₲ ${Number(s.order.total||0).toLocaleString("es-PY")}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">IVA (10%):</span>
            <span style="color: #374151; font-weight: 500;">₲ ${Number((s.order.total||0)*.1).toLocaleString("es-PY")}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Descuento:</span>
            <span style="color: #374151; font-weight: 500;">₲ 0</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; color: #d97706; border-top: 2px solid #d97706; padding-top: 6px; margin-top: 6px;">
            <span>TOTAL:</span>
            <span>₲ ${Number((s.order.total||0)*1.1).toLocaleString("es-PY")}</span>
          </div>
        </div>

        <!-- Información de Pago -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de Pago</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Método de Pago:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${s.order.payment_method==="efectivo"?"Efectivo":"Tarjeta"}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Estado:</td>
              <td style="padding: 3px 0; color: #059669; font-weight: bold; font-size: 10px;">Pagado</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha del Pedido:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${p(s.order.created_at)}</td>
              ${s.order.payment_id?`<td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">ID de Pago:</td><td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${s.order.payment_id}</td>`:"<td></td><td></td>"}
            </tr>
            ${s.order.notes?`
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Notas:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${s.order.notes}</td>
            </tr>
            `:""}
          </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">
          <p style="font-size: 12px; font-weight: bold; color: #d97706; margin-bottom: 4px;">¡Gracias por su compra en Daylen Cafetería!</p>
          <p style="font-size: 9px; margin-bottom: 3px;">Para consultas, contáctenos en daylencoffee@gmail.com</p>
          <p style="font-size: 8px; margin-bottom: 3px;">Teléfono: +595 986 195914</p>
          <p style="font-size: 7px; margin-top: 8px; color: #9ca3af;">Factura generada automáticamente el ${p(new Date)}</p>
        </div>
      </div>
    `,document.body.appendChild(d),await new Promise(o=>setTimeout(o,1e3)),console.log("📸 Capturando contenido con html2canvas...");const h=await window.html2canvas(d,{scale:2.5,useCORS:!0,allowTaint:!0,backgroundColor:"#ffffff",width:794,height:1123});console.log("✅ Contenido capturado exitosamente"),console.log("📄 Creando PDF en formato A4 optimizado con jsPDF...");const{jsPDF:S}=window.jspdf,b=new S({orientation:"portrait",unit:"mm",format:"a4",compress:!0}),y=210,w=297,v=h.height*y/h.width;let u=v;const k=h.toDataURL("image/png",.95);for(b.addImage(k,"PNG",0,0,y,v),u-=w;u>=0;)b.addPage(),b.addImage(k,"PNG",0,-w+u,y,v),u-=w;console.log("✅ PDF en formato A4 optimizado creado exitosamente"),console.log("⬇️ Descargando PDF..."),b.save(`factura-${s.order.id}.pdf`),document.body.removeChild(d),document.body.removeChild(n),console.log("✅ PDF descargado exitosamente")}catch(n){console.error("❌ Error generando PDF:",n);const p=document.querySelector('[style*="Generando PDF"]');p&&document.body.removeChild(p.parentElement)}};return(l,e)=>(x(),c(C,null,[z(D(N),{title:"Factura - Cafetería Daylen"}),z(L,null,{default:P(()=>[t("div",_,[z(D(M),{href:l.route("admin.orders.index"),class:"fixed top-4 right-4 z-50 group",title:"Cerrar factura"},{default:P(()=>[...e[0]||(e[0]=[t("div",{class:"relative w-12 h-12 flex items-center justify-center"},[t("div",{class:"absolute inset-0 bg-gradient-to-br from-red-500 to-red-600 rounded-full transform group-hover:scale-110 transition-transform duration-200 shadow-lg"}),t("svg",{class:"relative w-6 h-6 text-white transform group-hover:rotate-90 transition-transform duration-300",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"})])],-1)])]),_:1},8,["href"]),t("div",B,[t("div",I,[t("div",A,[e[2]||(e[2]=t("div",{class:"absolute top-0 right-0 w-32 h-32 bg-amber-500 opacity-10 rounded-full -mr-16 -mt-16"},null,-1)),e[3]||(e[3]=t("div",{class:"absolute bottom-0 left-0 w-64 h-64 bg-amber-500 opacity-5 rounded-full -ml-32 mb-10"},null,-1)),t("div",H,[t("div",V,[e[1]||(e[1]=t("div",{class:"mb-6 md:mb-0"},[t("div",{class:"flex items-center"},[t("h1",{class:"text-3xl font-bold font-serif"},"☕ Daylen"),t("span",{class:"ml-2 bg-amber-800 text-amber-100 text-xs px-2 py-1 rounded-full"},"PRO")]),t("p",{class:"text-amber-100 mt-1"},"Sistema de Facturación"),t("p",{class:"text-amber-200 text-sm mt-1"},"Factura Electrónica")],-1)),t("div",R,[t("div",Y," FACTURA #"+r(a.order.id.toString().padStart(6,"0")),1),t("p",G,"Emitida: "+r(j(a.order.created_at)),1)])])])]),t("div",U,[t("div",q,[e[15]||(e[15]=t("div",{class:"bg-amber-50 p-6 rounded-xl border border-amber-100"},[t("div",{class:"flex items-center mb-4"},[t("div",{class:"p-2 bg-amber-100 rounded-lg mr-3"},[t("svg",{class:"w-6 h-6 text-amber-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"})])]),t("h3",{class:"text-lg font-semibold text-gray-800"},"Información de la Empresa")]),t("div",{class:"space-y-2 text-gray-600 pl-11"},[t("p",{class:"flex items-start"},[t("span",{class:"text-amber-600 mr-2"},"•"),t("span",null,[t("span",{class:"font-medium"},"Empresa:"),i(" Daylen Cafetería")])]),t("p",{class:"flex items-start"},[t("span",{class:"text-amber-600 mr-2"},"•"),t("span",null,[t("span",{class:"font-medium"},"Dirección:"),i(" Calle Carlos Gómez, Barrio Remansito Sector 5, CDE")])]),t("p",{class:"flex items-start"},[t("span",{class:"text-amber-600 mr-2"},"•"),t("span",null,[t("span",{class:"font-medium"},"Teléfono:"),i(" +595 986 195914")])]),t("p",{class:"flex items-start"},[t("span",{class:"text-amber-600 mr-2"},"•"),t("span",null,[t("span",{class:"font-medium"},"Email:"),i(" daylencoffee@gmail.com")])]),t("p",{class:"flex items-start"},[t("span",{class:"text-amber-600 mr-2"},"•"),t("span",null,[t("span",{class:"font-medium"},"RUC:"),i(" 12345678-9")])])])],-1)),t("div",O,[e[14]||(e[14]=t("div",{class:"flex items-center mb-4"},[t("div",{class:"p-2 bg-blue-100 rounded-lg mr-3"},[t("svg",{class:"w-6 h-6 text-blue-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"})])]),t("h3",{class:"text-lg font-semibold text-gray-800"},"Información del Cliente")],-1)),t("div",K,[t("p",W,[e[5]||(e[5]=t("span",{class:"text-blue-600 mr-2"},"•",-1)),t("span",null,[e[4]||(e[4]=t("span",{class:"font-medium"},"Cliente:",-1)),i(" "+r(a.order.user.name),1)])]),t("p",Z,[e[7]||(e[7]=t("span",{class:"text-blue-600 mr-2"},"•",-1)),t("span",null,[e[6]||(e[6]=t("span",{class:"font-medium"},"Email:",-1)),i(" "+r(a.order.user.email),1)])]),t("p",J,[e[9]||(e[9]=t("span",{class:"text-blue-600 mr-2"},"•",-1)),t("span",null,[e[8]||(e[8]=t("span",{class:"font-medium"},"Nombre de Entrega:",-1)),i(" "+r(a.order.customer_name||"No especificado"),1)])]),t("p",Q,[e[11]||(e[11]=t("span",{class:"text-blue-600 mr-2"},"•",-1)),t("span",null,[e[10]||(e[10]=t("span",{class:"font-medium"},"Teléfono:",-1)),i(" "+r(a.order.phone||"No especificado"),1)])]),t("p",X,[e[13]||(e[13]=t("span",{class:"text-blue-600 mr-2"},"•",-1)),t("span",null,[e[12]||(e[12]=t("span",{class:"font-medium"},"Dirección:",-1)),i(" "+r(a.order.address||"No especificada"),1)])])])])]),t("div",tt,[e[19]||(e[19]=t("div",{class:"flex items-center mb-4"},[t("div",{class:"p-2 bg-green-100 rounded-lg mr-3"},[t("svg",{class:"w-6 h-6 text-green-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"})])]),t("h3",{class:"text-xl font-semibold text-gray-800"},"Detalles del Pedido")],-1)),t("div",et,[t("table",ot,[e[18]||(e[18]=t("thead",{class:"bg-gray-50"},[t("tr",null,[t("th",{class:"px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider"},"Producto"),t("th",{class:"px-6 py-4 text-center text-xs font-medium text-gray-700 uppercase tracking-wider"},"Cantidad"),t("th",{class:"px-6 py-4 text-right text-xs font-medium text-gray-700 uppercase tracking-wider"},"Precio Unit."),t("th",{class:"px-6 py-4 text-right text-xs font-medium text-gray-700 uppercase tracking-wider"},"Subtotal")])],-1)),t("tbody",st,[(x(!0),c(C,null,$(a.order.order_items,(n,p)=>(x(),c("tr",{key:n.id,class:T({"bg-gray-50":p%2===0,"hover:bg-gray-100 transition-colors duration-150":!0})},[t("td",rt,[t("div",nt,[t("div",at,[t("span",it,r(n.product.name.charAt(0)),1)]),t("div",null,[t("div",dt,r(n.product.name),1),n.size||n.sugar?(x(),c("div",lt,[n.size?(x(),c("span",pt,[e[16]||(e[16]=t("svg",{class:"w-3 h-3 mr-1",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"})],-1)),i(" "+r(n.size),1)])):f("",!0),n.sugar?(x(),c("span",mt,[e[17]||(e[17]=t("svg",{class:"w-3 h-3 mr-1",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"})],-1)),i(" "+r(n.sugar),1)])):f("",!0)])):f("",!0)])])]),t("td",ct,[t("span",xt,r(n.quantity),1)]),t("td",gt," ₲ "+r(Number(n.price).toLocaleString("es-PY",{maximumFractionDigits:0})),1),t("td",ft,[t("span",bt,"₲ "+r(Number(n.subtotal).toLocaleString("es-PY",{maximumFractionDigits:0})),1)])],2))),128))])])])]),t("div",ut,[t("div",ht,[e[29]||(e[29]=t("h3",{class:"text-lg font-semibold text-gray-800 mb-4 border-b border-amber-200 pb-2"},"Resumen de la Factura",-1)),t("div",yt,[t("div",wt,[e[20]||(e[20]=t("span",{class:"font-medium"},"Subtotal:",-1)),t("span",null,"₲ "+r(Number(a.order.total).toLocaleString("es-PY",{maximumFractionDigits:0})),1)]),t("div",vt,[e[21]||(e[21]=t("span",{class:"font-medium"},"IVA (10%):",-1)),t("span",null,"₲ "+r(Number(a.order.total*.1).toLocaleString("es-PY",{maximumFractionDigits:0})),1)]),e[23]||(e[23]=t("div",{class:"flex justify-between text-gray-700 mb-2"},[t("span",{class:"font-medium"},"Descuento:"),t("span",{class:"text-green-600"},"- ₲ 0")],-1)),e[24]||(e[24]=t("div",{class:"border-t border-amber-200 my-3"},null,-1)),t("div",zt,[e[22]||(e[22]=t("span",null,"Total:",-1)),t("span",kt,"₲ "+r(Number(a.order.total*1.1).toLocaleString("es-PY",{maximumFractionDigits:0})),1)])]),t("div",Dt,[t("div",Pt,[e[26]||(e[26]=t("div",{class:"flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center"},[t("svg",{class:"h-6 w-6 text-green-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M5 13l4 4L19 7"})])],-1)),t("div",Ct,[e[25]||(e[25]=t("h3",{class:"text-sm font-medium text-green-800"},"Pago Completado",-1)),t("div",jt,[t("p",null,[i(r(a.order.payment_method==="efectivo"?"Pago en efectivo":"Pago con tarjeta")+" ",1),a.order.payment_id?(x(),c("span",Ft,"ID: "+r(a.order.payment_id),1)):f("",!0)])])])])]),a.order.notes?(x(),c("div",Et,[t("div",St,[e[28]||(e[28]=t("div",{class:"flex-shrink-0 h-5 w-5 text-blue-400"},[t("svg",{fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})])],-1)),t("div",Nt,[e[27]||(e[27]=t("h3",{class:"text-sm font-medium text-blue-800"},"Notas del pedido",-1)),t("div",Mt,[t("p",null,r(a.order.notes),1)])])])])):f("",!0)])]),t("div",$t,[t("div",Tt,[e[30]||(e[30]=t("div",{class:"flex justify-center mb-4"},[t("div",{class:"bg-amber-100 p-3 rounded-full"},[t("svg",{class:"h-8 w-8 text-amber-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"})])])],-1)),e[31]||(e[31]=t("h3",{class:"text-lg font-medium text-gray-900"},"¡Gracias por su compra!",-1)),e[32]||(e[32]=t("p",{class:"mt-1 text-gray-600"},"Su satisfacción es nuestra mayor prioridad",-1)),e[33]||(e[33]=t("p",{class:"mt-4 text-sm text-gray-500"},[i(" Para consultas, contáctenos en "),t("a",{href:"mailto:daylencoffee@gmail.com",class:"text-amber-600 hover:text-amber-700 font-medium"},"daylencoffee@gmail.com")],-1)),t("p",Lt,"Factura generada el "+r(F(new Date)),1)])]),t("div",{class:"mt-10"},[t("div",{class:"bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300"},[t("div",{class:"max-w-md mx-auto"},[e[35]||(e[35]=t("div",{class:"flex justify-center mb-4"},[t("div",{class:"bg-white p-3 rounded-full shadow-md"},[t("svg",{class:"h-8 w-8 text-red-500",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"})])])],-1)),e[36]||(e[36]=t("h3",{class:"text-xl font-semibold text-gray-800 mb-2"},"¿Necesitas tu factura en PDF?",-1)),e[37]||(e[37]=t("p",{class:"text-gray-600 mb-6"},"Guarda una copia digital de tu factura para tus registros",-1)),t("button",{onClick:E,class:"group relative w-full max-w-xs mx-auto bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 overflow-hidden"},[...e[34]||(e[34]=[t("span",{class:"relative z-10 flex items-center"},[t("svg",{class:"w-5 h-5 mr-2 transform group-hover:scale-110 transition-transform",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"})]),t("span",null,"Descargar Factura PDF")],-1),t("span",{class:"absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors duration-300"},null,-1)])]),e[38]||(e[38]=t("p",{class:"mt-3 text-xs text-gray-500"},"Formato: PDF | Tamaño: ~200KB",-1))])])])])])])])]),_:1})],64))}};export{At as default};
