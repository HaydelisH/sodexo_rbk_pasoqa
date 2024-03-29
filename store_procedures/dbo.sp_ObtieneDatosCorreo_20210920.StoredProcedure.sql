USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtieneDatosCorreo_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
-- csb 26-03-2021 se agrega contratipo para adjuntar documento según configuracion en tabla correoadjuntos
-- =============================================
CREATE  PROCEDURE [dbo].[sp_ObtieneDatosCorreo_20210920] 	
AS
BEGIN
	SET NOCOUNT ON;

With DatosCorreos as
		(
		SELECT
				P.correo AS ToEmail,
				CO.CC AS CC,
				CO.CCo AS CCo,
				CO.Asunto AS Asunto,
				CO.Cuerpo AS Cuerpo,
				CO.Adjunta AS Adjunta,
				CO.PasswordAdd AS PasswordAdd,
				LEFT(P.personaid,4) AS Contraseña,
				'Documento.pdf' as NombreArchivo,
				--'1-9' AS RutEmpresa,
				C.RutEmpresa,
				--Null as ContratoTipo,
				CA.tipo as ContratoTipo,
				'DocumentosVariables' as TablaDatos,
				isnull(EN.documentoid,0) AS NumeroContrato,
				EN.Correlativo,
				CASE ISNULL(EN.RutUsuario, 'NULLVALUE') 
					WHEN 'NULLVALUE' then 1
					ELSE (ROW_NUMBER() over (Partition by EN.RutUsuario order by EN.RutUsuario asc)) 
				END AS linea	
				
			FROM EnvioCorreos EN
				INNER JOIN CorreosEstados CE on EN.CodCorreo = CE.estadoid
				INNER JOIN Correo CO on CE.CodCorreo = CO.CodCorreo			
				INNER JOIN personas P on EN.RutUsuario = P.personaid	
				INNER JOIN Contratos C on C.idDocumento = EN.documentoid
				--INNER JOIN ConfigCorreo CC ON CC.RutEmpresa = C.RutEmpresa
				LEFT JOIN CorreoAdjuntos CA on CA.Tipo = C.idPlantilla
			where 
					isNull(En.enviaCorreo,0) = 0 
					AND En.TipoCorreo = 0
					AND dbo.[ChkValidEmail](P.correo) = 1 
					--AND ( 
					--		( CE.CodCorreo = 2 AND datepart(HOUR, GETDATE()) = 7) 
					--	OR	( CE.CodCorreo != 2 )
					--)		
			UNION

			SELECT
				P.correo AS ToEmail,
				CO.CC AS CC,
				CO.CCo AS CCo,
				CO.Asunto AS Asunto,
				CO.Cuerpo AS Cuerpo,
				CO.Adjunta AS Adjunta,
				CO.PasswordAdd AS PasswordAdd,
				LEFT(P.personaid,4) AS Contraseña,
				'Documento.pdf' as NombreArchivo,
				'1-9' AS RutEmpresa,
				Null as ContratoTipo,
				'empleadoFormulario' as TablaDatos,
				EN.Correlativo AS NumeroContrato,
				EN.Correlativo,
				CASE ISNULL(EN.RutUsuario, 'NULLVALUE') 
					WHEN 'NULLVALUE' then 1
					ELSE (ROW_NUMBER() over (Partition by EN.RutUsuario order by EN.RutUsuario asc)) 
				END AS linea	
				
			FROM EnvioCorreos EN
				INNER JOIN CorreosEstados CE on EN.CodCorreo = CE.estadoid
				INNER JOIN Correo CO on CE.CodCorreo = CO.CodCorreo
			--INNER JOIN Correo CO on EN.CodCorreo = CO.CodCorreo
			INNER JOIN personas P on EN.RutUsuario = P.personaid	
			--LEFT JOIN ContratoFirmantes F on EN.RutFirmante = F.RutFirmante 
			--							AND EN.Estado = F.idEstado 
			--							AND EN.idDocumento = f.idContrato
			where 

					isNull(En.enviaCorreo,0) = 0 
					AND En.TipoCorreo IN (2)
					AND dbo.[ChkValidEmail](P.correo) = 1 
					
					--AND ( 
					--		( CE.CodCorreo = 2 AND datepart(HOUR, GETDATE()) = 7) 
					--	OR	( CE.CodCorreo != 2 )
					--)						


		)
		select 
			ToEmail,CC,CCo, DC.Asunto,Adjunta,NumeroContrato,Correlativo,PasswordAdd,Contraseña
			,NombreArchivo,/*'1-9'*/ RutEmpresa,DC.Cuerpo,ContratoTipo, DC.TablaDatos
		from DatosCorreos DC		 
		where 
		linea =1
		AND ToEmail != ''
	

	
	
END
GO
