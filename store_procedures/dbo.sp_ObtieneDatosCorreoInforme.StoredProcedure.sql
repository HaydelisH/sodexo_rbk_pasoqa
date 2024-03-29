USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtieneDatosCorreoInforme]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        RC
-- Create date: 20200121
-- Description:   Obtiene los datos para envio de correos
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtieneDatosCorreoInforme]      
AS
BEGIN
      SET NOCOUNT ON;
      
      With DatosCorreos as
            (
                  SELECT U.Usuarioid AS Rut, P.correo As ToEmail  ,C.CC, C.CCo, C.Asunto, C.Cuerpo,CI.adjunta, EC.Correlativo,CI.TablaDatos,EC.EnviaCorreo,EC.CodInforme
                    FROM [EnvioCorreosInforme] EC
                    inner join [dbo].CorreoInformes CI on EC.CodInforme = CI.CodInforme
                    inner join [dbo].[Correo] C on CI.CodCorreo = C.CodCorreo
                    inner join dbo.Usuarios U on EC.rutUsuario = U.usuarioid 
                    inner join dbo.personas P on P.personaid = U.usuarioid          
                  Where EnviaCorreo = 0                                                                                                                                                                            
            )
            select top 20 ToEmail,CC, CCo, DC.Asunto,Adjunta,Correlativo,DC.Cuerpo, DC.TablaDatos,CodInforme
            from DatosCorreos DC         
            where 
             ToEmail != ''

END
GO
