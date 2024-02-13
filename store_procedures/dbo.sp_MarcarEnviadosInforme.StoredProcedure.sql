USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_MarcarEnviadosInforme]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        RC
-- Create date: 20200121
-- Marca los registros una vez enviados
-- =============================================

CREATE PROCEDURE [dbo].[sp_MarcarEnviadosInforme]
      @Correlativo int
AS
BEGIN
      SET NOCOUNT OFF;  
            --Print 'actualiza unitario'
            UPDATE EnvioCorreosInforme
            SET EnviaCorreo = 1
            , FechaEnvio = GETDATE()
            WHERE Correlativo = @Correlativo    
      

END
GO
