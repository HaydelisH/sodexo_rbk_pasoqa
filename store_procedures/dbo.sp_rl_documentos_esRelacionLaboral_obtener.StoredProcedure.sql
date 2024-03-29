USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_documentos_esRelacionLaboral_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Modificado por: Gdiaz 11/01/2021
-- Descripcion: Obtiene llos datos de firmantes segun el flujo de la plantilla
-- Ejemplo:exec sp_rl_documentos_esRelacionLaboral_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_documentos_esRelacionLaboral_obtener]
	@idDocumento INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
    -- Insert statements for procedure here
   
    BEGIN
        SELECT
            ISNULL(WorkflowProceso.tipoWF, 0) AS tipoWF
        FROM
            Contratos
        INNER JOIN 
            WorkflowProceso
        ON
            WorkflowProceso.idWF = Contratos.idWF 
            AND
            Contratos.idDocumento = @idDocumento
	END
END
GO
