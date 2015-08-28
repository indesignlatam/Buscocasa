@extends('layouts.home')

@section('head')
    <title>{{ Settings::get('site_name') }}</title>
    <meta property="og:title" content="{{ Settings::get('site_name') }}"/>
    <meta property="og:image" content="{{ asset('/images/defaults/facebook-share.jpg') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ Settings::get('site_description') }}"/>
@endsection

@section('css')
	@parent
@endsection

@section('navbar')
@show

@section('content')
	@include('includes.navbarHome')
	<div class="uk-container uk-container-center" style="max-width:800px">
		<div class="uk-text-center">
			<img class="" src="{{ asset('/images/support/user/welcome.png') }}" width="600px">
		</div>
		<div class="uk-margin-top">
			<h1>TÉRMINOS Y CONDICIONES</h1>

			<p>Esta página establece los "Términos y Condiciones" bajo los cuales usted puede usar BuscoCasa.co. Por favor lea esta página cuidadosamente. Si usted no acepta los términos y condiciones establecidos aquí, no use BuscoCasa.co ni sus servicios. Mediante el uso de este Sitio Web usted está indicando su aceptación a estos Términos y Condiciones. INDESIGN COLOMBIA S.A.S. puede modificar estos Términos y Condiciones en cualquier momento. Usted debe visitar esta página periódicamente para revisar los TÉRMINOS y Condiciones debido a que los mismos son obligatorios para usted. Los términos "usted" o "usuario" tal como se usan aquí, se refieren a todas las personas naturales o jurídicas o entidades de cualquier naturaleza que accedan a este Sitio Web por cualquier razón.</p>


			<h2>USO DEL MATERIAL</h2>

			<p>INDESIGN COLOMBIA S.A.S. lo autoriza a usted a consultar, revisar y usar el material que se encuentra en el Sitio Web, únicamente para su uso personal y no comercial. El contenido de este Sitio Web, incluyendo pero sin limitarse a, los textos, gráficas, imágenes, logotipos, iconos, software y cualquier otro material como calculadoras están protegidos bajo las leyes colombianas de derechos de autor, leyes de propiedad industrial y otras leyes aplicables. Todo el Material es de propiedad de INDESIGN COLOMBIA S.A.S. o de sus proveedores o clientes. El uso no autorizado del material puede constituir una violación de las leyes colombianas o extranjeras sobre derechos de autor, leyes de propiedad industrial u otras leyes. Usted no podrá vender o modificar el Material en manera alguna, ni ejecutar o anunciar públicamente el Material ni distribuirlo para propósitos comerciales. Usted no podrá copiar o adaptar el código HTML que INDESIGN COLOMBIA S.A.S. crea para generar sus páginas ya que el mismo está protegido por los derechos de autor de INDESIGN COLOMBIA S.A.S.</p>


			<h2>USO AUTORIZADO DEL SITIO WEB</h2>

			<p>Reglas Generales: Los usuarios no pueden usar el Sitio Web con el fin de transmitir, distribuir, almacenar o destruir material (i) en violación de cualquier ley aplicable o regulación, (ii) de manera que se infrinjan las leyes sobre derechos de autor, propiedad industrial, secretos comerciales o cualquier otro derecho de propiedad intelectual de terceros o de manera que viole la privacidad, publicidad u otros derechos personales de terceros, o (iii) en forma que sea difamatoria, obscena, amenazante o abusiva. Reglas de Seguridad del Sitio Web: A los usuarios les está prohibido violar o intentar violar la seguridad del Sitio Web. Específicamente los usuarios no podrán (i) acceder a información que no esté dirigida o autorizada a dicho usuario o acceder a servidores o cuentas a los cuales el usuario no está autorizado a acceder, (ii) intentar probar la vulnerabilidad de un sistema o red sin la debida autorización o violar las medidas de seguridad o autenticación, (iii) intentar interferir con los servicios prestados a un usuario, servidor o red, incluyendo pero sin limitarse al envió de virus a través del Sitio Web, (iv) enviar correo electrónico no solicitado, incluyendo promociones y/o publicidad de productos o servicios. La violación de cualquier sistema o red de seguridad puede resultar en responsabilidades civiles y penales. INDESIGN COLOMBIA S.A.S. investigará la ocurrencia de hechos que puedan constituir violaciones a lo anterior y cooperará con cualquier autoridad competente en la persecución de los usuarios que estén envueltos en tales violaciones.</p> 


			<h2>USOS PROHIBIDOS DEL SITIO WEB</h2>

			<p>El Sitio Web puede ser usado únicamente para propósitos legales. INDESIGN COLOMBIA S.A.S. prohíbe el uso del Sitio Web en cualquiera de las siguientes formas:</p>

			<p>Incluir en el Sitio Web cualquier información sobre el producto a vender u ofrecer, falsa o inexacta o información que no corresponda a la realidad.
			Incluir en el Sitio Web cualquier derecho de franquicia, esquema de pirámide, membresía a un club o grupo, representación de ventas, agencia comercial o cualquier oportunidad de negocios que requiera un pago anticipado o pagos periódicos, solicitando el reclutamiento de otros miembros, sub-distribuidores o sub-agentes.
			Borrar o revisar cualquier material incluido en el Sitio Web por cualquiera otra persona o entidad, sin la debida autorización.
			Usar cualquier elemento, diseño, software o rutina para interferir o intentar interferir con el funcionamiento adecuado de este Sitio Web o cualquier actividad que sea llevada a cabo en el Sitio Web.<br>
			Intentar decifrar, de compilar o desensamblar cualquier software comprendido en el Sitio Web o que de cualquier manera haga parte del Sitio Web.
			En general, incluir o colocar en el Sitio Web información falsa, inexacta, incompleta o engañosa.
			Si usted tiene un password o contraseña que le permita el acceso a un área no pública de este Sitio Web, no podrá revelar o compartir ese password o contraseña con terceras personas o usar el password o contraseña para propósitos no autorizados.</p>


			<h2>INFORMACIÓN DE LOS USUARIOS</h2>

			<p>Cuando usted se registra en este Sitio Web, se le solicitará que suministre a INDESIGN COLOMBIA S.A.S. cierta información, incluyendo pero sin limitarse a, una dirección válida de correo electrónico (su "Información"). En adición a los términos y condiciones que puedan ser previstos en otras políticas de privacidad en este Sitio Web, usted entiende y acuerda que la compañía puede revelar a terceras personas, sobre bases anónimas, cierta información contenida en su solicitud de registro. INDESIGN COLOMBIA S.A.S. no revelará a terceras personas su nombre, dirección, dirección de correo electrónico o número telefónico sin su consentimiento expresado a través de las diferentes herramientas o sistemas previstos en el Sitio Web, salvo en la medida en que sea necesario o apropiado para cumplir con las leyes aplicables o con procesos o procedimientos legales en los que tal información sea pertinente. INDESIGN COLOMBIA S.A.S. se reserva el derecho de ofrecer a usted servicios o productos de INDESIGN COLOMBIA S.A.S. o de terceras personas, basados en las preferencias que usted haya identificado en su solicitud de registro o en cualquier momento posterior a la misma. Tales ofertas podrán ser hechas por INDESIGN COLOMBIA S.A.S. o por terceras personas.</p>


			<h2>INFORMACIÓN INCLUIDA EN EL SITIO WEB POR LOS USUARIOS</h2>

			<p>Como usuario usted es responsable por sus propias comunicaciones e información y por las consecuencias de incluir o colocar dicha información o comunicaciones en el Sitio Web. Usted no podrá: (i) incluir o colocar en el Sitio Web material que esté protegido por las leyes sobre derechos de autor, a menos que usted sea el propietario de tales derechos o haya obtenido permiso del propietario de tales derechos para incluir tal material en el Sitio Web, (ii) incluir o colocar en el Sitio Web material que revele secretos industriales o comerciales a menos que usted sea el propietario de los mismos o haya obtenido autorización del propietario, (iii) incluir en el Sitio Web material que de cualquier forma pueda implicar una violación de derechos de propiedad intelectual o industrial o cualquier otro derecho, (iv) incluir material que sea obsceno, difamatorio, abusivo, amenazante u ofensivo para cualquier otro usuario o cualquier otra persona o entidad, (v) incluir en el Sitio Web imágenes o declaraciones pornográficas o que incluyan sexo explícito, o que sea considerada pornografía infantil en los términos del Decreto 1524 de 2002 (vi) incluir o colocar en el Sitio Web publicidad o anuncios publicitarios sin la debida autorización de INDESIGN COLOMBIA S.A.S., cadenas de cartas, virus, caballos de troya, bombas de tiempo o cualquier programa de computador o herramienta con la intención de dañar, interferir, interceptar o apropiarse de cualquier sistema, datos o información. INDESIGN COLOMBIA S.A.S. no otorga garantía alguna, expresa o implícita, acerca de la veracidad, exactitud o confiabilidad de la información incluida en el Sitio Web por los usuarios ni apoya o respalda las opiniones expresadas por los usuarios. Usted reconoce y declara que la confianza por usted depositada en cualquier material incluido en el Sitio Web por los usuarios se hará bajo su propio riesgo. INDESIGN COLOMBIA S.A.S. actúa como un medio pasivo para la distribución y publicación en Internet de información presentada por los usuarios y no tiene obligación de revisar anticipadamente tal información ni es responsable de revisar o monitorear la información incluida en el Sitio Web por los usuarios. Si INDESIGN COLOMBIA S.A.S. es notificada por un usuario acerca de la existencia de información que no cumpla con estos TÉRMINOS y Condiciones, la Compañía puede investigar tal información y determinar de buena fe y a su exclusiva discreción si remueve o elimina tal información o solicita que sea removida o eliminada del Sitio Web. INDESIGN COLOMBIA S.A.S. se reserva el derecho de expulsar usuarios o de prohibir su acceso futuro al Sitio Web por violación de estos Términos y Condiciones o de la ley aplicable. Igualmente la Compañía se reserva el derecho de eliminar del Sitio Web información presentada o incluida por un usuario, cuando lo considere apropiado o necesario a su exclusiva discreción, si estima o cree que tal información puede generar responsabilidad para INDESIGN COLOMBIA S.A.S. o puede causar la pérdida de los servicios de sus proveedores de internet (ISPs) o de otros proveedores.</p>


			<h2>REGISTRO Y CONTRASEÑA</h2>

			<p>Usted es responsable por mantener la confidencialidad de su contraseña. Usted será responsable por todos los usos de su registro en el Sitio Web, sean o no autorizados por usted. Usted acuerda notificar inmediatamente a INDESIGN COLOMBIA S.A.S. cualquier uso no autorizado de su registro y contraseña.</p>


			<h2>PROHIBICIÓN DE REVENTA, CESIÓN O USO COMERCIAL NO AUTORIZADO</h2>

			<p>Usted acuerda no revender o ceder sus derechos u obligaciones bajo estos Términos y Condiciones. Usted acuerda igualmente no hacer un uso comercial no autorizado de este Sitio Web.</p>


			<h2>TERMINACIÓN</h2>

			<p>INDESIGN COLOMBIA S.A.S. se reserva el derecho, a su exclusiva discreción, de borrar toda la información que usted haya incluido en el Sitio Web y de terminar inmediatamente su registro y el acceso al Sitio Web o a determinados servicios proveídos por INDESIGN COLOMBIA S.A.S., ante el incumplimiento por su parte de estos Términos y Condiciones o ante la imposibilidad de verificar o autenticar cualquier información que usted haya presentado en su forma de registro para acceder al Sitio Web.</p>

			<h2>DISPOSICIONES GENERALES</h2>

			<p>INDESIGN COLOMBIA S.A.S. no asegura que el material pueda ser legalmente accesado o visto fuera del territorio de la República de Colombia. El acceso al Material puede no ser legal por ciertas personas o en ciertos países. Si usted tiene acceso a este Sitio Web desde un lugar ubicado fuera del territorio de la República de Colombia, lo hace bajo su propio riesgo y es responsable del cumplimiento de las leyes aplicables en su jurisdicción. Estos Términos y Condiciones están regidos por las leyes de la República de Colombia, sin dar aplicación a las normas o principios sobre conflicto de leyes. La jurisdicción para cualquier reclamación que surja de estos Términos y Condiciones será exclusivamente la de los tribunales y jueces de la República de Colombia. Si alguna previsión de estos términos y condiciones es declarada nula o inválida o ineficaz, ello no afectará la validez de las restantes previsiones de estos Términos y Condiciones.</p>

			<h2>RESPONSABILIDAD DE LA COMPAÑÍA</h2>

			<p>INDESIGN COLOMBIA S.A.S. actúa solamente como un lugar o escenario para que los oferentes y/o vendedores, incluyan o publiquen los bienes y servicios que deseen ofrecer. INDESIGN COLOMBIA S.A.S. no revisa ni censura las ofertas, bienes o servicios publicados. INDESIGN COLOMBIA S.A.S. no está involucrada ni se involucra en las transacciones o tratos entre los oferentes y/o vendedores y los usuarios de este sitio web. La selección de uno y otro servicio y/o producto es responsabilidad exclusiva de los usuarios. CEET no avala, certifica, garantiza ni recomienda los productos y servicios ofrecidos a través del portal. INDESIGN COLOMBIA S.A.S. no tiene ni ejerce control alguno sobre la calidad, idoneidad, seguridad o legalidad de los servicios y/o productos ofrecidos o publicados en su portal. INDESIGN COLOMBIA S.A.S. tampoco tiene ni ejerce control alguno sobre la veracidad o exactitud de la información publicada por los vendedores u oferentes, sobre los productos y/o servicios ofrecidos.</p>

			<p>Adicionalmente por favor tenga en cuenta que existen riesgos, que incluyen pero no se limitan, al daño o lesiones físicas, al tratar con extraños, menores de edad o personas que actúan bajo falsas pretensiones. Usted asume todos los riesgos asociados con tratar con otros usuarios con los cuales usted entre en contacto a través del portal. El cumplimiento de los términos y condiciones de los contratos o acuerdos que usted llegue a celebrar con otros usuarios del Sitio Web, así como el cumplimiento de las leyes aplicables a tales contratos o acuerdos, es responsabilidad exclusiva de los usuarios y no de INDESIGN COLOMBIA S.A.S.. Debido a que la autenticidad de los usuarios de internet es difícil, INDESIGN COLOMBIA S.A.S. no puede confirmar y no confirma que cada usuario es quien dice ser. Debido a que la Compañía no se involucra en las relaciones o tratos entre sus usuarios ni controla el comportamiento de los usuarios o participantes en el portal, en el evento en que usted tenga una disputa con uno o más usuarios del Sitio Web, usted libera a INDESIGN COLOMBIA S.A.S. (y a sus empleados y agentes) de cualquier reclamación, demanda o daño de cualquier naturaleza, que surja de o de cualquier otra forma se relacione con dicha disputa.</p>

			<p>INDESIGN COLOMBIA S.A.S. no controla la información suministrada por otros usuarios y que pueda estar disponible a través del Sitio Web. Por su propia naturaleza la información proveniente de terceras personas puede ser ofensiva, dañina, falsa o inexacta y en algunos casos puede ser titulada o rotulada de manera errónea o decepcionante. INDESIGN COLOMBIA S.A.S. espera que usted emplee la debida precaución y sentido común cuando use este Sitio Web. El Material puede contener inexactitudes o errores tipográficos. INDESIGN COLOMBIA S.A.S. no otorga garantía alguna, expresa o implícita, acerca de la precisión, exactitud, confiabilidad u oportunidad del Sitio Web o del Material. Nada de lo incluido en el Sitio Web por INDESIGN COLOMBIA S.A.S. o por los usuarios constituye recomendación, asesoría o consejo suministrado por INDESIGN COLOMBIA S.A.S.. El uso del Sitio Web y del material, al igual que las decisiones que usted adopte con base en este Sitio Web y el Material, se hacen bajo su propio y exclusivo riesgo. INDESIGN COLOMBIA S.A.S. recomienda que todas las decisiones que usted pretenda adoptar con base en el Material y cualquier otra información incluida en el Sitio Web sean consultadas con sus propios asesores y consultores. INDESIGN COLOMBIA S.A.S. no será responsable por cualquier decisión de compra o negocio que usted tomo con base en uso de este WebSite.</p>

			<p>INDESIGN COLOMBIA S.A.S. NO GARANTIZA QUE EL SITIO WEB OPERE LIBRE ERRORES O QUE EL SITIO WEB Y SU SERVIDOR SE ENCUENTRE LIBRE DE VIRUS DE COMPUTADORES U OTROS MECANISMOS DAÑINOS. SI EL USO DEL SITIO WEB O DEL MATERIAL RESULTA EN LA NECESIDAD DE PRESTAR SERVICIO DE REPARACIÓN O MANTENIMIENTO A SUS EQUIPOS O INFORMACIÓN O DE REEMPLAZAR SUS EQUIPOS O INFORMACIÓN, INDESIGN COLOMBIA S.A.S. NO ES RESPONSABLE POR LOS COSTOS QUE ELLO IMPLIQUE.</p>


			<p>EL SITIO WEB Y EL MATERIAL SE PONEN A DISPOSICIÓN EN EL ESTADO EN QUE SE ENCUENTREN. INDESIGN COLOMBIA S.A.S. NO OTORGA GARANTÍA ALGUNA SOBRE LA EXACTITUD, CONFIABILIDAD U OPORTUNIDAD DEL MATERIAL, LOS SERVICIOS, LOS TEXTOS, EL SOFTWARE, LAS GRÁFICAS Y LOS LINKS O VÍNCULOS.</p>

			<p>EN NINGÚN CASO INDESIGN COLOMBIA S.A.S., SUS PROVEEDORES O CUALQUIER PERSONA MENCIONADA EN EL SITIO WEB SERÁ RESPONSABLE POR DAÑOS DE CUALQUIER NATURALEZA, RESULTANTES DEL USO O LA IMPOSIBILIDAD DE USAR EL SITIO WEB O EL MATERIAL. LINKS A OTROS SITIO WEBS El SITIO WEB contiene links o vínculos a SITIO WEBs de terceras personas. Estos links o vínculos se suministran para su conveniencia únicamente y INDESIGN COLOMBIA S.A.S. no respalda, recomienda o asume responsabilidad alguna sobre el contenido de los SITIO WEBs de terceras personas. Si usted decide acceder a través de los links o vínculos a los SITIO WEBs de terceras personas, lo hace bajo su propio riesgo.</p>

			<h2>PROMOCIONES, CONCURSOS Y EVENTOS</h2>

			<p>Las promociones, concursos, sorteos y eventos que se implementen en el Portal estarán sujetas a las reglas y condiciones que en cada oportunidad se establezca por parte de INDESIGN COLOMBIA S.A.S., siendo necesario como requisito mínimo para acceder a tales oportunidades o beneficios comerciales, que el Usuario se encuentre debidamente registrado como usuario del Portal. INDESIGN COLOMBIA S.A.S. no se responsabiliza por cualquier tipo de daño -incluyendo moral, físico, material, ni de cualquier otra índole- que pudiera invocarse como relacionado con la recepción por parte del Usuario registrado de cualquier tipo de obsequios y/o regalos remitidos por INDESIGN COLOMBIA S.A.S.. Así mismo, INDESIGN COLOMBIA S.A.S. no será responsable por las consecuencias que pudieren causar el ingreso al Portal y/o la presencia en cualquier evento y/o reunión organizada por éste. El Usuario reconoce que INDESIGN COLOMBIA S.A.S. no asume responsabilidad alguna que corresponda a un anunciante y/o el proveedor de los servicios que se ofrezcan en el Portal, siendo entendido que INDESIGN COLOMBIA S.A.S. no se responsabiliza por la calidad ni la entrega de los productos o prestación de servicios que se publican en este sitio. Por tal motivo no será responsable por cualquier problema, queja o reclamo de los usuarios por cuestiones atinentes a dichos productos y/o servicios. Cada promoción, concurso o evento que se promueva o realice a través del Portal, estará sujeto a las reglas de Privacidad que para el mismo se indiquen, por lo que la participación en los mismos deberá atenerse a lo que en cada caso se señale, lo cual será complementario a las políticas de privacidad señaladas anteriormente, siempre y que no sea excluyente.</p>


			<h2>BASES DE DATOS E INFORMACIÓN</h2>
			<p>Quien diligencia el formulario de registro acepta que sus datos se incorporen a las bases de datos de INDESIGN COLOMBIA S.A.S., sus filiales o subsidiarias. En todo caso, el Usuario podrá en cualquier momento solicitar el retiro de su información personal de las bases de datos de INDESIGN COLOMBIA S.A.S., sus filiales o subsidiarias, para lo cual deberá enviar un e - mail a contactenos@BuscoCasa.co ó cliente@BuscoCasa.co, en cuyo caso perderá los privilegios derivados del registro. Así mismo, el Usuario podrá modificar o actualizar la información suministrada en cualquier momento, ingresando a la Zona de Usuario en el Portal.</p>

			<p>Quien diligencia el formulario de registro autoriza de modo expreso a INDESIGN COLOMBIA S.A.S. y sus filiales o subsidiarias para recolectar, procesar y comercializar los datos contenidos en el mismo. Con base en lo anterior INDESIGN COLOMBIA S.A.S., sus filiales o subsidiarias podr´n reproducir, publicar, traducir, adaptar, extraer o compendiar los datos o información suministrada. Del mismo modo le confiere la facultad para disponer de ellos a título oneroso o gratuito bajo las condiciones lícitas que su libre criterio dicte. A su vez, quien diligencia el formulario de registro declara que conoce y acepta que los datos contenidos en el mismo pueden ser utilizados para impulsar, dirigir, ejecutar y de manera general, llevar a cabo campañas promocionales o concursos de carácter comercial o publicitario, de INDESIGN COLOMBIA S.A.S. y sus filiales o subsidiarias o de otras personas o sociedades con quien esta contrate tales actividades, mediante el envío de Email, Mensaje de texto (SMS y/o MMS) o a través de cualquier medio análogo y/o digital de comunicación.</p>

			<h2>DERECHO AL RETRACTO</h2>
			<p>El usuario comprador cuenta con cinco (5) días hábiles contados a partir de la solicitud de publicación del aviso a BuscoCasa.co, para ejercer el derecho de retracto, estas solicitudes deberán enviarse al equipo de servicio al cliente de BuscoCasa.co. El derecho de retracto podrá ejercerse por parte de los usuarios compradores, con excepción de que el servicio de publicación haya comenzado a ejecutarse o que se haya ejecutado, caso en el cual aplica lo dispuesto en el artículo 47 de la Ley 1480 de 2011.</p>

			<h2>GARANTÍA</h2>
			<p>El usuario comprador cuenta con cinco (5) días hábiles contados a partir del primer día de publicación, para hacer efectiva la garantía, reportar falla, solicitar modificaciones o insatisfacción del producto adquirido, estas solicitudes deberán enviarse al equipo de servicio al cliente de BuscoCasa.co.</p>


		</div>
 
    </div>
</html>
@endsection

@section('js')
	@parent
@endsection