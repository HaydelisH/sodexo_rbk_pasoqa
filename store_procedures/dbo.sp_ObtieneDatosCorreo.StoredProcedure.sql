USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtieneDatosCorreo]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtieneDatosCorreo] 	
AS
BEGIN
	SET NOCOUNT ON;

    With DatosCorreos as
		(
			SELECT -- RRHH
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
				(select top 1 Tipo from CorreoAdjuntos CA where C.idPlantilla = CA.Tipo and C.RutEmpresa = CA.RutEmpresa) as ContratoTipo,
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
			where 
					isNull(En.enviaCorreo,0) = 0 
					AND En.TipoCorreo = 0
					 AND DATEDIFF(SS,EN.FechaCreacion,getdate()) > CE.tiempo
					
					--AND ( 
					--		( CE.CodCorreo = 2 AND datepart(HOUR, GETDATE()) = 7) 
					--	OR	( CE.CodCorreo != 2 )
					--)		
					and dbo.ChkValidEmail(p.correo) = 1

            UNION
			SELECT -- RRLL
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
				Null as ContratoTipo,
				CASE EN.TipoCorreo 
					WHEN 2 THEN 'datosRRLL'
				END AS TablaDatos,
				isnull(EN.Correlativo,0) AS NumeroContrato,
				EN.Correlativo,
                1 AS linea
			FROM EnvioCorreos EN
				INNER JOIN CorreosEstados CE on EN.CodCorreo = CE.estadoid
				INNER JOIN Correo CO on CE.CodCorreo = CO.CodCorreo			
				LEFT JOIN personas P on EN.RutUsuario = P.personaid	
				INNER JOIN Contratos C on C.idDocumento = EN.documentoid
				INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF AND WorkflowProceso.tipoWF = 1
			WHERE 
					isNull(En.enviaCorreo,0) = 0 
					AND En.TipoCorreo = 2	
					 AND DATEDIFF(SS,EN.FechaCreacion,getdate()) > CE.tiempo
 
					AND P.correo like '%@%'
				-- validaciones correos malos
					AND P.correo not like '%Ã%'	
					AND P.correo not like '%..%'	
					AND P.correo not like '%.@%'
					AND P.correo not like '%,%'	
					AND P.correo not like '%:%'	
					AND P.correo not like '%}%'		
					AND dbo.ChkValidEmail(P.correo) = 1 
			UNION
			--SELECT -- BLOQUEO DE CUENTA
			--	P.correo AS ToEmail,
			--	CO.CC AS CC,
			--	CO.CCo AS CCo,
			--	CO.Asunto AS Asunto,
			--	CO.Cuerpo AS Cuerpo,
			--	CO.Adjunta AS Adjunta,
			--	CO.PasswordAdd AS PasswordAdd,
			--	LEFT(P.personaid,4) AS Contraseña,
			--	'Documento.pdf' as NombreArchivo,
			--	'1-9' AS RutEmpresa,
			--	--C.RutEmpresa,
			--	Null as ContratoTipo,
			--	CASE EN.CodCorreo 
   --                 WHEN 16 THEN 'datosBloqueoCuenta'
   --                 ELSE 'DocumentosVariables' 
   --             END as TablaDatos,
   --             CASE EN.CodCorreo 
   --                 WHEN 16 THEN  EN.Correlativo
   --                 ELSE isnull(EN.documentoid,0) 
   --             END AS NumeroContrato,
   --             --isnull(EN.documentoid,0) AS NumeroContrato,
			--	EN.Correlativo,
			--	CASE ISNULL(EN.RutUsuario, 'NULLVALUE') 
			--		WHEN 'NULLVALUE' then 1
			--		ELSE (ROW_NUMBER() over (Partition by EN.RutUsuario order by EN.RutUsuario asc)) 
			--	END AS linea 
				
			--FROM EnvioCorreos EN
			--	INNER JOIN CorreosEstados CE on EN.CodCorreo = CE.estadoid
			--	INNER JOIN Correo CO on CE.CodCorreo = CO.CodCorreo   
			--	INNER JOIN personas P on EN.RutUsuario = P.personaid 
			--	--INNER JOIN Contratos C on C.idDocumento = EN.documentoid
			--	--INNER JOIN ConfigCorreo CC ON CC.RutEmpresa = C.RutEmpresa
			--	--LEFT JOIN CorreoAdjuntos CA on C.idPlantilla = CA.tipo and C.RutEmpresa = CA.RutEmpresa
			--WHERE 
			--		isNull(En.enviaCorreo,0) = 0 
			--		AND En.TipoCorreo = 1
			--		AND En.CodCorreo not in ( 1, 7)
   --                 -- ENVIO EN HORARIO LABORAL LUNES A VIERNES 08 A 19 hrs.
   --                 --AND DATEPART(DW,@NOW)NOT IN (1,7)
   --                 --AND (DATEPART(HOUR,@NOW)> 7 AND DATEPART(HOUR, @NOW)<20)
   --                 AND DATEDIFF(SS,EN.FechaCreacion,getdate()) > CE.tiempo
			--		--AND ( 
			--		--		( CE.CodCorreo = 2 AND datepart(HOUR, GETDATE()) = 7) 
			--		--	OR	( CE.CodCorreo != 2 )
			--		--)		
   --                 --AND dbo.[ChkValidEmail](P.correo) = 1    
   --                 --AND dbo.[ChkValidEmail](P.correoinstitucional) = 1		
            --UNION

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
				isnull(EN.documentoid,0) AS NumeroContrato,
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
			WHERE 

					isNull(En.enviaCorreo,0) = 0 
					AND En.TipoCorreo IN (2)
					AND CE.codcorreo not in (15,16,17)
					--AND ( 
					--		( CE.CodCorreo = 2 AND datepart(HOUR, GETDATE()) = 7) 
					--	OR	( CE.CodCorreo != 2 )
					--)						
						and dbo.ChkValidEmail(P.correo) = 1 																									
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
