USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtieneDatosCorreoUsuarios]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtieneDatosCorreoUsuarios] 	
AS
BEGIN
	SET NOCOUNT ON;

With DatosCorreos as
		(
		select  
			
						P.correo AS ToEmail,	
						Co.CC,
						Co.CCo,
						Co.Asunto,
						Co.Cuerpo,				
						Co.Adjunta,
						personaid as RutUsuario,
						En.Correlativo,
						Co.Passwordadd,						
						substring(P.personaid,1,4) AS Contraseña,
						'Archivo.pdf' as NombreArchivo,
						isnull(U.RutEmpresa, '1-9')  AS RutEmpresa,												
						'Usuarios' AS TablaDatos,
						Null AS ContratoTipo
					from EnvioCorreos En							
							INNER JOIN CorreosEstados CE on EN.CodCorreo = CE.estadoid
							INNER JOIN Correo CO on CE.CodCorreo = CO.CodCorreo		
							INNER JOIN Personas P on En.RutUsuario = P.personaid							
							Inner join usuarios U on En.RutUsuario = U.usuarioid						
					
							--inner Join Correo Co on En.codCorreo = Co.codCorreo
							
				where 																	
						 En.enviaCorreo = 0						
						AND En.TipoCorreo = 1					
						  																											
		)
		select ToEmail,CC,CCo, DC.Asunto,Adjunta,RutUsuario,Correlativo,PasswordAdd,Contraseña,NombreArchivo, RutEmpresa,DC.Cuerpo,ContratoTipo, DC.TablaDatos
		from DatosCorreos DC
		--left join CorreoBody CB on DC.Estado = CB.Estado and DC.TablaDatos = CB.TablaDatos
		 -- CB.Asunto as Asunto,
		 -- CB.CuerpoEmail as Cuerpo
		 
		where --linea =1 AND
		ToEmail != ''
	
	
END
GO
