USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ConfiguracionCorreo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        RC
-- Create date: 20170214
-- Description:   Obtiene configuracion para el envio de correo
-- =============================================
CREATE PROCEDURE [dbo].[sp_ConfiguracionCorreo] 
@Empresa varchar(12) ='1-9'
AS
BEGIN
      SET NOCOUNT ON;

         SELECT TOP 1 [Host]
              ,[FromName]
              ,[UserMail]
              ,[Pass]
              ,[Port]
              ,[PortSsl]
              ,[IsHtml]
			,[IsExchange]
			,[Dominio]
			,[Usuario]              
        FROM [dbo].[ConfigCorreo]
		where rutempresa = @Empresa
END
GO
